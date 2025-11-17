import cv2
import ctypes
import numpy as np
import json
import os

# SCREEN RESOLUTION
user32 = ctypes.windll.user32
screen_width = user32.GetSystemMetrics(0)
screen_height = user32.GetSystemMetrics(1)

# CONFIG
IMAGE_PATH = r"\Heat Map\Court Reference\court_reference.png"
SAVE_PATH = r"\Heat Map\Court Reference"
WINDOW_NAME = "Click Court Corners"

points = []

def click_event(event, x, y, flags, param):
    if event == cv2.EVENT_LBUTTONDOWN:
        points.append((x, y))
        print(f"Point {len(points)}: ({x}, {y})")
        cv2.circle(img_display, (x, y), 5, (0, 255, 0), -1)
        cv2.putText(img_display, f"{len(points)}", (x + 10, y - 10),
                    cv2.FONT_HERSHEY_SIMPLEX, 0.6, (255, 255, 255), 2)
        cv2.imshow(WINDOW_NAME, img_display)

# LOAD AND RESIZE IMAGE
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

# DISPLAY AND CAPTURE CLICKS
cv2.imshow(WINDOW_NAME, img_display)
cv2.setMouseCallback(WINDOW_NAME, click_event)

print("🖱 Click on the court corners in order: top-left, top-right, bottom-left, bottom-right.")
cv2.waitKey(0)
cv2.destroyAllWindows()

# SAVE COORDINATES
os.makedirs(os.path.dirname(SAVE_PATH), exist_ok=True)
with open(SAVE_PATH, 'w') as f:
    json.dump(points, f, indent=2)
print(f"\n✅ Court keypoints saved to: {SAVE_PATH}")