clc; 
clear; 
close all;

disp("Opening webcam...");
c=webcam;
disp("Loading network (myNet1)...");
load myNet1;
disp("Loading face detector...");
faceDetector=vision.CascadeObjectDetector;

disp("Starting recognition... Close figure window to stop.");
hFig = figure;
set(hFig, 'CloseRequestFcn', 'global RUNNING; RUNNING = false;');
global RUNNING;
RUNNING = true;

while RUNNING
e = c.snapshot;
bboxes = step(faceDetector,e);
    if ~isempty(bboxes)
        labels = strings(size(bboxes, 1), 1);
        for i = 1:size(bboxes, 1)
            currentBBox = bboxes(i, :);
            es = imcrop(e, currentBBox);
            es = imresize(es, [227 227]);
            labels(i) = string(classify(myNet1, es));
        end
        e = insertObjectAnnotation(e, 'rectangle', bboxes, labels, ...
            'FontSize', 18, 'Color', 'green', 'LineWidth', 3);
            imshow(e);
            title('');
    else
        imshow(e);
        title('No Face Detected');
    end
    drawnow;
end
disp("Stopping webcam.");
clear c;
close all;
clear global RUNNING;