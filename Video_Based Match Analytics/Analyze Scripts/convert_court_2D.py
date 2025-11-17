import cv2
import numpy as np
import os
import ctypes

# SCREEN RESOLUTION
user32 = ctypes.windll.user32
screen_width = user32.GetSystemMetrics(0)
screen_height = user32.GetSystemMetrics(1)

# CONFIGURATION
HEATMAP_PATH = r"\Heat Map\Court Reference\court_mask.png"
COURT_REF_PATH = r"\Heat Map\Court Reference\court_reference.png"
OUTPUT_PATH = r"\Heat Map\Court Reference"
os.makedirs(OUTPUT_PATH, exist_ok=True)

# LOAD IMAGES
heatmap = cv2.imread(HEATMAP_PATH)
court_ref = cv2.imread(COURT_REF_PATH)

# PRECISE COURT KEYPOINTS FROM LABEL STUDIO
src_pts = np.array([
    [1148, 1047],   # top-left
    [1579, 1039],   # top-right
    [2110, 1851],   # bottom-right
    [646, 1876],    # bottom-left
], dtype=np.float32)

# TARGET COURT DIMENSIONS (top-down view)
court_width = 800
court_height = 800
dst_pts = np.array([
    [0, 0],                         # top-left
    [court_width, 0],               # top-right
    [court_width, court_height],    # bottom-right
    [0, court_height],              # bottom-left
], dtype=np.float32)

# COMPUTE HOMOGRAPHY
H, _ = cv2.findHomography(src_pts, dst_pts)

# WARP HEATMAP TO TOP-DOWN VIEW
warped_heatmap = cv2.warpPerspective(heatmap, H, (court_width, court_height))

# DRAW COURT LINES
court_bg = np.zeros((court_height, court_width, 3), dtype=np.uint8)
line_color = (255, 255, 255)
thickness = 2

# Outer boundary
cv2.rectangle(court_bg, (0, 0), (court_width - 1, court_height - 1), line_color, thickness)

# Net line (center horizontal)
cv2.line(court_bg, (0, court_height // 2), (court_width, court_height // 2), line_color, thickness)

# Service lines
cv2.line(court_bg, (0, int(court_height * 0.25)), (court_width, int(court_height * 0.25)), line_color, thickness)
cv2.line(court_bg, (0, int(court_height * 0.75)), (court_width, int(court_height * 0.75)), line_color, thickness)

# Center vertical line
cv2.line(court_bg, (court_width // 2, 0), (court_width // 2, court_height), line_color, thickness)

# DRAW NET LINE FROM ANNOTATED POINTS
net_pts = np.array([
    [1025, 1240],  # net_left
    [1711, 1227],  # net_right
], dtype=np.float32)

warped_net = cv2.perspectiveTransform(net_pts[None, :, :], H)[0]
cv2.line(court_bg,
         tuple(warped_net[0].astype(int)),
         tuple(warped_net[1].astype(int)),
         (0, 255, 255), 2)  # Yellow net line

# NORMALIZE HEATMAP
heatmap = cv2.normalize(heatmap, None, 0, 255, cv2.NORM_MINMAX)

# OVERLAY HEATMAP ON COURT
overlay = cv2.addWeighted(court_bg, 0.3, warped_heatmap, 0.7, 0)

# RESIZE TO FIT SCREEN
resized_overlay = cv2.resize(overlay, (screen_width, screen_height))

# DISPLAY
cv2.imshow("Top-Down Heatmap", resized_overlay)
cv2.waitKey(0)
cv2.destroyAllWindows()

# SAVE OUTPUT
output_file = os.path.join(OUTPUT_PATH, "topdown_heatmap_resized.png")
cv2.imwrite(output_file, resized_overlay)
print(f"✅ Resized top-down heatmap saved to {output_file}")