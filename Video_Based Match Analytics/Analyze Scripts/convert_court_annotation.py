import cv2
import numpy as np
import json
import os
import ctypes

# SCREEN RESOLUTION
user32 = ctypes.windll.user32
screen_width = user32.GetSystemMetrics(0)
screen_height = user32.GetSystemMetrics(1)

# CONFIGURATION
ANNOTATION_PATH = r"\Heat Map\Court_dataset\dataset_json\full_court_coordinates.json"
COURT_REF_PATH = r"\Heat Map\Court Reference\court_mask.png"
OUTPUT_PATH = r"\Heat Map\Court Reference"
os.makedirs(OUTPUT_PATH, exist_ok=True)

# LOAD COURT IMAGE
court_ref = cv2.imread(COURT_REF_PATH)

# LOAD AND CONVERT ANNOTATIONS
with open(ANNOTATION_PATH) as f:
    data = json.load(f)

keypoints = data[0]["kp-1"]
img_w = keypoints[0]["original_width"]
img_h = keypoints[0]["original_height"]

pixel_coords = {}
for kp in keypoints:
    label = kp["keypointlabels"][0]
    x_px = int((kp["x"] / 100) * img_w)
    y_px = int((kp["y"] / 100) * img_h)
    pixel_coords[label] = [x_px, y_px]

# HOMOGRAPHY USING OUTER COURT CORNERS
src_pts = np.array([
    pixel_coords["top-left"],
    pixel_coords["top-right"],
    pixel_coords["bottom-right"],
    pixel_coords["bottom-left"]
], dtype=np.float32)

court_width = 800
court_height = 800
dst_pts = np.array([
    [0, 0],
    [court_width, 0],
    [court_width, court_height],
    [0, court_height]
], dtype=np.float32)

H, _ = cv2.findHomography(src_pts, dst_pts)

# WARP COURT IMAGE TO TOP-DOWN VIEW
warped_court = cv2.warpPerspective(court_ref, H, (court_width, court_height))

# DRAW TACTICAL OVERLAY
overlay = warped_court.copy()

def warp_and_draw(label1, label2, color=(0, 255, 0), thickness=2):  # Green lines
    pt1 = np.array([pixel_coords[label1]], dtype=np.float32)
    pt2 = np.array([pixel_coords[label2]], dtype=np.float32)
    warped = cv2.perspectiveTransform(np.array([pt1, pt2]), H)
    cv2.line(overlay,
             tuple(warped[0][0].astype(int)),
             tuple(warped[1][0].astype(int)),
             color, thickness)

# DRAW NET LINE
warp_and_draw("net-left", "net-right")

# DRAW SERVICE LINES
warp_and_draw("serviceline_top_left", "serviceline_top_right")
warp_and_draw("serviceline_bottom_left", "serviceline_bottom_right")

# DRAW MIDLINES
warp_and_draw("midline_top_top", "midline_bottom_bottom")

# DRAW INSIDE COURT BOXES
warp_and_draw("inside_left_top", "inside_left_bottom")
warp_and_draw("inside_right_top", "inside_right_bottom")
warp_and_draw("inside_top_left", "inside_top_right")
warp_and_draw("inside_bottom_left", "inside_bottom_right")

# RESIZE TO FIT SCREEN
resized_overlay = cv2.resize(overlay, (screen_width, screen_height))

# DISPLAY
cv2.imshow("Top-Down Tactical Court", resized_overlay)
cv2.waitKey(0)
cv2.destroyAllWindows()

# SAVE OUTPUT
output_file = os.path.join(OUTPUT_PATH, "topdown_tactical_overlay.png")
cv2.imwrite(output_file, resized_overlay)
print(f"✅ Tactical overlay saved to {output_file}")