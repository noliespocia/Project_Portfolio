# Computer Vision System Portfolio
The collection of all my Computer Vision Projects. This portfolio contains Computer Vision Projects using Python,  

## Video-based Match Analytics using YOLOv8m Model.
The objectives of this project is to train the YOLOv8m model to detect badminton player and shuttlecock. Using the trained model, my aim is to detect the player, player speed, shuttlecock, shuttlecock speed, player heatmap, shuttlecock heatmap, determine if the shuttlecock is outside or inside, and count the number of shots per rally.

### Features
-   Modular training script with YOLOv8m as base model for training. Supports any model variant of YOLOv8 but runs YOLOv8m model as base model.
-   Customizable settings for training. (Number of epoch, batch size, image resolution, and learning rate).
-   Aggressive Augmentation (mosaic, mixup, HSV shifts, flips). (Optional)
-   Auto-generated evaluation. (mAP, precision, and recall metrics)
-   Analyze scripts used to analyze video of badminton matches.
-   Automated splitting of dataset for train and validation.

Training Configuration
-   model, yolov8m.pt
-   data, data.yaml
-   epochs, 150
-   imgsz, 640
-   batch, 16
-   name, vbma_train
-   lr0, 0.01
-   project, runs/detect
-   verbose, True
-   patience, 50
-   weight_decay, 0.0005
-   momentum, 0.937
Optional Augmentation (--augment flag)
-   hsv_h, 0.015
-   hsv_s, 0.7
-   hsv_v, 0.4
-   degrees, 10.0
-   translate, 0.1
-   scale, 0.5
-   shear, 2.0
-   flipud, 0.1
-   fliplr, 0.5
-   mosaic, 1.0
-   mixup, 0.2

Dataset folder structure instruction:
1. Place your images in:
- yolo_dataset/image/train/
- yolo_dataset/image/val/
2. Place all labelled .txt files in:
- data/labels/
3. Run the script to copy labels into
- yolo_dataset/labels/train/
- yolo_dataset/labels/val/

Note: This is an ongoing project, I'm still currently engineering the scripts and the model as part of our thesis. Precision for shuttlecock is still low, but i'm still working on it.

## Real-time face recognition
The objectives of this project is to utilize Alexnet in real-time face recognition. Implements a face recognition training pipeline using transfer learning with AlexNet in MATLAB.

### Features
1. Transfer Learning with Alexnet
-   Loads the pretrained AlexNet model and replaces the final fully connected and classification layers to match the number of face classes in the dataset.
2. Automatic Dataset Handling
-   Loads images from subfolders (each subfolder = one identity)
-   Automatically splits the dataset into 70% training and 30% validation
3. Data Augmentation
-   Applies random horizontal reflection and pixel translations to increase dataset diversity and reduce overfitting.
4. Training Configuration
-   Optimizer: Stochastic Gradient Descent with Momentum (SGDM)
-   Batch size: 32
-   Epochs: 30
-   Learning rate: 0.0001
-   Early stopping via validation patience
-   Live training progress visualization

