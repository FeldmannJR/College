package dev.feldmann.fia.mlp.layers;

import dev.feldmann.fia.Utils;
import dev.feldmann.fia.mlp.perceptrons.Perceptron;

public abstract class Layer {

    public Layer previous;
    public Layer next;

    int size;
    int inputSize;
    int outputSize;
    public Perceptron[] perceptrons;
    int bias = 0;

    public Layer(int inputSize, int size, int outputSize) {
        this.size = size;
        this.inputSize = inputSize;
        this.outputSize = outputSize;
        this.perceptrons = new Perceptron[size];
        createPerceptrons();
    }

    public abstract void createPerceptrons();

    public void setNext(Layer next) {
        this.next = next;
    }

    public void setPrevious(Layer previous) {
        this.previous = previous;
    }

    public double[] sumWeighted(double[] input) {
        if (input.length != inputSize) {
            throw new RuntimeException("Tentando passar vetor com tamanho diferente do esperado! Expected: " + inputSize + " got: " + input.length);
        }
        double[] d = new double[size];
        for (int i = 0; i < perceptrons.length; i++) {
            Perceptron p = perceptrons[i];
            double sum = p.sum(input);
            d[i] = sum;
        }
        return d;
    }

    public double[] calculateValues(double[] input) {
        // Calcula a somatória dos pesos
        double[] h = sumWeighted(input);
        // Passa os valores pela funcão signodal
        h = Utils.forEach(h, Utils::sigmoid);
        return h;
    }


}
