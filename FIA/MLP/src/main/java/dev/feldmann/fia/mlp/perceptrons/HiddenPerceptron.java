package dev.feldmann.fia.mlp.perceptrons;

import dev.feldmann.fia.mlp.layers.Layer;

public class HiddenPerceptron extends Perceptron {


    public HiddenPerceptron(Layer layer, int positionInLayer, int inputSize) {
        super(layer, positionInLayer, inputSize);
    }

    @Override
    public double calculateGradient(double[] outputs, double[] layerOutput, double[] targets) {
        double o = layerOutput[positionInLayer];
        double sum = 0;
        for (int x = 0; x < weights.length; x++) {
            double w = weights[x];
            double g = layer.next.perceptrons[x].calculateGradient(outputs, outputs, targets);
            sum += w * g;
        }
        return o * (1 - o) * sum;
    }
}
