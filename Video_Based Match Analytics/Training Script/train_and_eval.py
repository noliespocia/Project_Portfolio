import os
import argparse
from ultralytics import YOLO

def main():
    # Parse CLI arguments
    parser = argparse.ArgumentParser()
    parser.add_argument('--model', type=str, default='yolov8m.pt', help='Base model to start training from')
    parser.add_argument('--data', type=str, default='data.yaml', help='Path to data.yaml file')
    parser.add_argument('--epochs', type=int, default=150, help='Number of training epochs')
    parser.add_argument('--imgsz', type=int, default=640, help='Input image size')
    parser.add_argument('--batch', type=int, default=16, help='Batch size')
    parser.add_argument('--name', type=str, default='vbma_train', help='Run name for saving results')
    parser.add_argument('--lr0', type=float, default=0.01, help='Initial learning rate')
    parser.add_argument('--augment', action='store_true', help='Enable aggressive augmentation')
    args = parser.parse_args()

    # Load model
    model = YOLO(args.model)

    # Training configuration
    train_args = {
        'data': args.data,
        'epochs': args.epochs,
        'imgsz': args.imgsz,
        'batch': args.batch,
        'name': args.name,
        'lr0': args.lr0,
        'project': 'runs/detect',
        'verbose': True,
        'patience': 50,
        'weight_decay': 0.0005,
        'momentum': 0.937
    }

    # Optional: aggressive augmentation
    if args.augment:
        train_args.update({
            'hsv_h': 0.015,
            'hsv_s': 0.7,
            'hsv_v': 0.4,
            'degrees': 10.0,
            'translate': 0.1,
            'scale': 0.5,
            'shear': 2.0,
            'flipud': 0.1,
            'fliplr': 0.5,
            'mosaic': 1.0,
            'mixup': 0.2
        })

    # Train the model
    print("🔧 Starting training...")
    results = model.train(**train_args)

    # Automatically evaluate best weights
    best_model_path = os.path.join('runs', 'detect', args.name, 'weights', 'best.pt')
    print(f"\n✅ Training complete. Evaluating best model: {best_model_path}")
    model = YOLO(best_model_path)
    metrics = model.val(data=args.data)

    # Display key metrics
    print("\n📊 Evaluation Results:")
    print(f"mAP@0.5: {metrics.box.map50:.4f}")
    print(f"mAP@0.5:0.95: {metrics.box.map:.4f}")
    print(f"Precision: {metrics.box.precision:.4f}")
    print(f"Recall: {metrics.box.recall:.4f}")

# Windows multiprocessing fix
if __name__ == '__main__':
    from multiprocessing import freeze_support
    freeze_support()
    main()