clc;
clear;
close all;

% start yung webcam
cam = webcam;
faceDetector = vision.CascadeObjectDetector;

% load
load myNet1;

% Loop
while true
    % Capture frame
    frame = snapshot(cam);
    
    % Detect faces
    bboxes = step(faceDetector, frame);
    
    if ~isempty(bboxes)
        labels = strings(size(bboxes, 1), 1); % Preallocate label array
        
        for i = 1:size(bboxes, 1)
            % Crop and resize each detected face
            faceImg = imcrop(frame, bboxes(i, :));
            faceImg = imresize(faceImg, [227 227]);
            
            % Classify face
            label = classify(myNet1, faceImg);
            labels(i) = string(label);
        end
        
        % Annotate frame with bounding boxes and labels
        annotatedFrame = insertObjectAnnotation(frame, 'rectangle', bboxes, cellstr(labels));
    else
        % No face detected
        annotatedFrame = insertText(frame, [10 10], 'No Face Detected', ...
            'FontSize', 18, 'BoxColor', 'red', 'TextColor', 'white');
    end
    
    % Display result
    imshow(annotatedFrame);
    drawnow;
end