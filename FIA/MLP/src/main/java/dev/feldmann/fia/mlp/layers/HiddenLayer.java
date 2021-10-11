package dev.feldmann.fia.mlp.layers;

import dev.feldmann.fia.mlp.perceptrons.HiddenPerceptron;
import dev.feldmann.fia.mlp.perceptrons.OutputPerceptron;

public class HiddenLayer extends Layer {


    public HiddenLayer(int inputSize, int size, int outputSize) {
        super(inputSize, size, outputSize);
    }

    @Override
    public void createPerceptrons() {
        for (int x = 0; x < size; x++) {
            perceptrons[x] = new HiddenPerceptron(this, x, outputSize);
        }
    }
}
