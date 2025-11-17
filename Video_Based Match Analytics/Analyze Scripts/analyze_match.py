import cv2
import json
import numpy as np
import csv
import os
import ctypes
from ultralytics import YOLO

# Screen dimensions for display
user32 = ctypes.windll.user32
screen_width = user32.GetSystemMetrics(0)
screen_height = user32.GetSystemMetrics(1)

# PATHS
VIDEO_PATH = r"\gopro vids\2. Video-converted USE.mp4"
MODEL_PATH = r"\YOLO_MODEL_FOLDER\runs\detect\yolov8m_shuttle_player\weights\best.pt"
MASK_PATH = r"\YOLO_MODEL_FOLDER\Heat Map\Court Reference\topdown_tactical_overlay.png"
NET_REF_PATH = r"\YOLO_MODEL_FOLDER\Heat Map\Net Reference\net_reference.json"
PLAYER_LOG_PATH = r"\YOLO_MODEL_FOLDER\Heat Map\Player Log Path\player_logs.csv"
HEATMAP_DIR = r"\YOLO_MODEL_FOLDER\Heat Map\Heat Map"
FRAME_SKIP = 10
SHOW_VIDEO = True

# court perspective points
src_pts = np.array([
    [1148, 1047],   # top-left
    [1579, 1039],   # top-right
    [2110, 1851],   # bottom-right
    [646, 1876],    # bottom-left
], dtype=np.float32)

dst_pts = np.array([
    [0, 0],
    [800, 0],
    [800, 800],
    [0, 800]
], dtype=np.float32)

H, _ = cv2.findHomography(src_pts, dst_pts)

# yolo model setup
model = YOLO(MODEL_PATH)
model.fuse()

# ensures heatmap matches court size
court = cv2.imread(MASK_PATH)
court = cv2.resize(court, (800, 800))

with open(NET_REF_PATH) as f:
    net_ref = json.load(f)

# video setup
cap = cv2.VideoCapture(VIDEO_PATH)
fps = cap.get(cv2.CAP_PROP_FPS)
frame_id = 0
prev_positions = {'Player': {}, 'Shuttle': {}}
player_logs = []
heatmap_accumulator = np.zeros((800, 800), dtype=np.float32)

# utility function to convert pixels/sec to km/h
def pixels_to_kmh(pixels_per_sec):
    meters_per_sec = pixels_per_sec * 0.0015
    return meters_per_sec * 3.6

# main processing loop
while cap.isOpened():
    ret, frame = cap.read()
    if not ret:
        break
    if frame_id % FRAME_SKIP != 0:
        frame_id += 1
        continue

    results = model(frame)[0]
    detections = results.boxes.data.cpu().numpy()
    class_names = model.names
    player_positions = []

    for det in detections:
        x1, y1, x2, y2, conf, cls = det
        cls_name = class_names.get(int(cls), f"cls_{int(cls)}")
        cx, cy = int((x1 + x2) / 2), int(y2)

        # speed calculation
        if cls_name in prev_positions:
            if frame_id - FRAME_SKIP in prev_positions[cls_name]:
                px, py = prev_positions[cls_name][frame_id - FRAME_SKIP]
                dist = np.linalg.norm([cx - px, cy - py])
                speed = dist * fps / FRAME_SKIP
            else:
                speed = 0
            prev_positions[cls_name][frame_id] = (cx, cy)
        else:
            speed = 0

        # player processing
        if cls_name == 'Player':
            player_positions.append((cy, cx, speed, (int(x1), int(y1), int(x2), int(y2))))

            # warp and accumulate heatmap
            pt = np.array([[cx, cy]], dtype=np.float32)
            pt_warped = cv2.perspectiveTransform(pt[None, :, :], H)[0][0]
            wx, wy = int(pt_warped[0]), int(pt_warped[1])

            if 0 <= wy < 800 and 0 <= wx < 800:
                heatmap_accumulator[wy, wx] += 1

        # shuttle processing
        if cls_name == 'Shuttle' and conf > 0.25:
            kmh = pixels_to_kmh(speed)
            cv2.rectangle(frame, (int(x1), int(y1)), (int(x2), int(y2)), (0, 0, 255), 2)
            cv2.putText(frame, f"Shuttle: {kmh:.1f} km/h", (int(x1), int(y1) - 10),
                        cv2.FONT_HERSHEY_SIMPLEX, 0.6, (255, 255, 255), 2)

    # sort and label players
    player_positions.sort()
    labels = ['Player A', 'Player B', 'Player C', 'Player D']
    for i, (cy, cx, speed, box) in enumerate(player_positions[:4]):
        label = labels[i]
        kmh = pixels_to_kmh(speed)
        x1, y1, x2, y2 = box
        cv2.rectangle(frame, (x1, y1), (x2, y2), (0, 255, 0), 2)
        cv2.putText(frame, f"{label}: {kmh:.1f} km/h", (x1, y1 - 10),
                    cv2.FONT_HERSHEY_SIMPLEX, 0.6, (255, 255, 255), 2)
        player_logs.append([frame_id, label, cx, cy])

    # net line
    cv2.line(frame, tuple(net_ref["left"]), tuple(net_ref["right"]), (0, 255, 255), 2)

    # overlays text in the screen
    cv2.putText(frame, f"Number of Players: {len(player_positions)}", (20, 50),
                cv2.FONT_HERSHEY_SIMPLEX, 2.0, (255, 255, 255), 2)
    cv2.putText(frame, f"Frame: {frame_id}", (20, 90),
                cv2.FONT_HERSHEY_SIMPLEX, 1.0, (255, 255, 255), 2)
    cv2.putText(frame, f"FPS: {fps:.2f}", (20, 130),
                cv2.FONT_HERSHEY_SIMPLEX, 1.0, (255, 255, 255), 2)
    cv2.putText(frame, f"Press 'Esc' to Exit", (20, frame.shape[0] - 20)
                , cv2.FONT_HERSHEY_SIMPLEX, 1.0, (255, 255, 255), 2)

    # display
    if SHOW_VIDEO:
        resized_frame = cv2.resize(frame, (screen_width, screen_height))
        cv2.imshow('Match Analysis', resized_frame)
        if cv2.waitKey(1) == 27:
            break

    frame_id += 1

cap.release()
cv2.destroyAllWindows()
print(f"✅ Analysis complete. Frames processed: {frame_id}")

# save player logs
os.makedirs(os.path.dirname(PLAYER_LOG_PATH), exist_ok=True)
with open(PLAYER_LOG_PATH, 'w', newline='') as f:
    writer = csv.writer(f)
    writer.writerow(['frame', 'player_id', 'x', 'y'])
    writer.writerows(player_logs)
print(f"✅ Player logs saved to {PLAYER_LOG_PATH}")

# final heatmap processing
heatmap = cv2.GaussianBlur(heatmap_accumulator, (0, 0), sigmaX=25, sigmaY=25)
heatmap = cv2.normalize(heatmap, None, 0, 255, cv2.NORM_MINMAX)
heatmap_color = cv2.applyColorMap(heatmap.astype(np.uint8), cv2.COLORMAP_JET)
overlay = cv2.addWeighted(court, 0.6, heatmap_color, 0.4, 0)

# save heatmap
os.makedirs(HEATMAP_DIR, exist_ok=True)
existing = [f for f in os.listdir(HEATMAP_DIR) if f.startswith('player_heatmap') and f.endswith('.png')]
next_index = len(existing) + 1
heatmap_filename = f"player_heatmap_{next_index}.png"
heatmap_path = os.path.join(HEATMAP_DIR, heatmap_filename)

cv2.imwrite(heatmap_path, overlay)
print(f"✅ Player heatmap saved to {heatmap_path}")