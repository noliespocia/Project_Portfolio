import os, random, shutil

SOURCE_DIR = r'data\images' #replace
DEST_DIR = r'yolo_dataset'  #replace
TRAIN_RATIO = 0.8

os.makedirs(f'{DEST_DIR}/images/train', exist_ok=True)
os.makedirs(f'{DEST_DIR}/images/val', exist_ok=True)

images = [f for f in os.listdir(SOURCE_DIR) if f.endswith(('.jpg', '.png'))]
random.shuffle(images)
split = int(len(images) * TRAIN_RATIO)

for i, img in enumerate(images):
    target = 'train' if i < split else 'val'
    shutil.copy(os.path.join(SOURCE_DIR, img), os.path.join(f'{DEST_DIR}/images/{target}', img))