package dev.feldmann.fia.mlp.perceptrons;

import dev.feldmann.fia.mlp.layers.Layer;

public class OutputPerceptron extends Perceptron {


    public OutputPerceptron(Layer layer, int positionInLayer, int inputSize) {
        super(layer, positionInLayer, inputSize);
    }

    public double calculateGradient(double[] outputs, double[] layerOutput, double[] targets) {
        double o = layerOutput[positionInLayer];
        double t = targets[positionInLayer];
        return o * (1 - o) * (t - o);
    }

}
