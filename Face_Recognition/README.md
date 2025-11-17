Real-time face recognition
The objectives of this project is to utilize Alexnet in real-time face recognition. Implements a face recognition training pipeline using transfer learning with AlexNet in MATLAB.

Features
Transfer Learning with Alexnet
Loads the pretrained AlexNet model and replaces the final fully connected and classification layers to match the number of face classes in the dataset.
Automatic Dataset Handling
Loads images from subfolders (each subfolder = one identity)
Automatically splits the dataset into 70% training and 30% validation
Data Augmentation
Applies random horizontal reflection and pixel translations to increase dataset diversity and reduce overfitting.
Training Configuration
Optimizer: Stochastic Gradient Descent with Momentum (SGDM)
Batch size: 32
Epochs: 30
Learning rate: 0.0001
Early stopping via validation patience
Live training progress visualization
