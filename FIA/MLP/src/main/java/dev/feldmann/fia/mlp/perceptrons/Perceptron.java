package dev.feldmann.fia.mlp.perceptrons;

import dev.feldmann.fia.Utils;
import dev.feldmann.fia.mlp.layers.Layer;

public abstract class Perceptron {


    Layer layer;
    int positionInLayer;

    int nextLayerSize;
    public double weights[];

    public double bias = 1;
    public double biasWeight = 1;

    public Perceptron(Layer layer, int positionInLayer, int nextLayerSize) {
        this.nextLayerSize = nextLayerSize;
        this.layer = layer;
        this.positionInLayer = positionInLayer;
        this.weights = Utils.generateRandomWeights(nextLayerSize, -1, 1);
    }

    public double sum(double[] input) {
        double sum = 0;
        for (int i = 0; i < weights.length; i++) {
            sum += weights[i] * input[i];
        }
        return sum;
    }

    public abstract double calculateGradient(double[] outputs, double[] layerOutput, double[] targets);


    public int avaliacao(double valor) {
        return valor < 0 ? -1 : 1;
    }


}
