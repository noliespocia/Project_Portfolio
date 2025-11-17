import os, shutil

IMAGE_DIR = r'\yolo_dataset\images'
LABEL_SRC = r'\data\labels'
LABEL_DEST = r'\yolo_dataset\labels'

for split in ['train', 'val']:
    image_files = os.listdir(os.path.join(IMAGE_DIR, split))
    os.makedirs(os.path.join(LABEL_DEST, split), exist_ok=True)

    for img in image_files:
        label_name = os.path.splitext(img)[0] + '.txt'
        src_path = os.path.join(LABEL_SRC, label_name)
        dest_path = os.path.join(LABEL_DEST, split, label_name)

        if os.path.exists(src_path):
            shutil.copy(src_path, dest_path)