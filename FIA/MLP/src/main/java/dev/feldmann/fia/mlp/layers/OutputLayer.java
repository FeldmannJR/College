package dev.feldmann.fia.mlp.layers;

import dev.feldmann.fia.mlp.perceptrons.OutputPerceptron;
import dev.feldmann.fia.mlp.perceptrons.Perceptron;

public class OutputLayer extends Layer {


    public OutputLayer(int inputSize, int size, int outputSize) {
        super(inputSize, size, outputSize);
    }

    @Override
    public void createPerceptrons() {
        for (int x = 0; x < size; x++) {
            perceptrons[x] = new OutputPerceptron(this, x, outputSize);
        }
    }
}
