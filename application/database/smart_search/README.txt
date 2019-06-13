The order of compilation & execution of the packages and/or classes in this folder is the following:


Math.sql -> Matrix.sql -> Map.sql -> NeuralNetwork.sql

'training_data.txt' is therefore NOT to be compiled.


As a note, NeuralNetwork.sql may take a while to complete its execution due to the network's training. It may take up to one hour.

IF you may not have that time at your disposal, then you can decrease the amount of training the neural network does. 
To do so, decrease the number given as parameter to the training() function around the end of the script. 
