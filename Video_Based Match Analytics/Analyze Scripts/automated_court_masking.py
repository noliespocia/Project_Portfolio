import cv2
import numpy as np
import json
import os
import ctypes

# SCREEN RESOLUTION
user32 = ctypes.windll.user32
screen_width = user32.GetSystemMetrics(0)
screen_height = user32.GetSystemMetrics(1)

# CONFIG
IMAGE_PATH = r"\Heat Map\Court Reference\court_reference.png"
SAVE_PATH = r"\Heat Map\Court Reference\court_keypoints_auto.json"
WINDOW_NAME = "Auto Court Detection"

# LOAD IMAGE
img = cv2.imread(IMAGE_PATH)
h, w = img.shape[:2]
scale = min(screen_width / w, screen_height / h)
new_w, new_h = int(w * scale), int(h * scale)
resized_img = cv2.resize(img, (new_w, new_h))

# CENTER ON BLACK CANVAS
canvas = np.zeros((screen_height, screen_width, 3), dtype=np.uint8)
x_offset = (screen_width - new_w) // 2
y_offset = (screen_height - new_h) // 2
canvas[y_offset:y_offset+new_h, x_offset:x_offset+new_w] = resized_img
img_display = canvas.copy()

# PROCESS IMAGE FOR CONTOURS
gray = cv2.cvtColor(resized_img, cv2.COLOR_BGR2GRAY)
blurred = cv2.GaussianBlur(gray, (5, 5), 0)
edges = cv2.Canny(blurred, 50, 150)
contours, _ = cv2.findContours(edges, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

court_contour = None
max_area = 0
for cnt in contours:
    area = cv2.contourArea(cnt)
    if area > 10000:
        approx = cv2.approxPolyDP(cnt, 0.02 * cv2.arcLength(cnt, True), True)
        if len(approx) == 4 and area > max_area:
            court_contour = approx
            max_area = area

if court_contour is None:
    print("❌ No court-like contour found.")
    exit()

# EXTRACT AND SORT CORNERS
pts = court_contour.reshape(4, 2)

def sort_corners(pts):
    pts = sorted(pts, key=lambda x: x[1])
    top = sorted(pts[:2], key=lambda x: x[0])
    bottom = sorted(pts[2:], key=lambda x: x[0])
    return np.array([top[0], top[1], bottom[1], bottom[0]], dtype=np.float32)

sorted_pts = sort_corners(pts)

# OFFSET POINTS TO MATCH CANVAS POSITION
offset_pts = sorted_pts + np.array([x_offset, y_offset], dtype=np.float32)

# DRAW POINTS FOR VISUAL DEBUG
for i, pt in enumerate(offset_pts):
    pt_int = tuple(pt.astype(int))
    cv2.circle(img_display, pt_int, 10, (0, 255, 255), -1)
    cv2.putText(img_display, f"{i+1}", (pt_int[0] + 10, pt_int[1] - 10),
                cv2.FONT_HERSHEY_SIMPLEX, 0.6, (255, 255, 255), 2)

cv2.imshow(WINDOW_NAME, img_display)
cv2.waitKey(0)
cv2.destroyAllWindows()

# SAVE ORIGINAL-SCALE POINTS TO JSON
# Convert back to original image scale
original_pts = sorted_pts / scale
os.makedirs(os.path.dirname(SAVE_PATH), exist_ok=True)
with open(SAVE_PATH, 'w') as f:
    json.dump(original_pts.tolist(), f, indent=2)

print(f"✅ Auto-detected court keypoints saved to: {SAVE_PATH}")