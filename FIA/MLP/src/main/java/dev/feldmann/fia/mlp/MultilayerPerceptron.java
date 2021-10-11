package dev.feldmann.fia.mlp;

import dev.feldmann.fia.Entry;
import dev.feldmann.fia.EntryParser;
import dev.feldmann.fia.mlp.layers.HiddenLayer;
import dev.feldmann.fia.mlp.layers.Layer;
import dev.feldmann.fia.mlp.layers.OutputLayer;
import dev.feldmann.fia.mlp.perceptrons.Perceptron;

import java.util.Arrays;

public class MultilayerPerceptron {

    int inputs;
    int hiddens;
    int outputs;


    Layer hidden;
    Layer output;

    public static double learningRate = 0.2;

    public MultilayerPerceptron(int inputs, int hiddens, int outputs) {
        this.inputs = inputs;
        this.hiddens = hiddens;
        this.outputs = outputs;
        hidden = new HiddenLayer(inputs, hiddens, outputs);
        output = new OutputLayer(hiddens, outputs, 1);
        hidden.setNext(output);
        output.setPrevious(hidden);
    }

    public void init() {

    }

    public double[] feedForward(double[] input) {
        // Calcula os pesos da camada escondida
        double[] h = this.hidden.calculateValues(input);
        double[] o = this.output.calculateValues(h);
        return o;

    }


    @Override
    public String toString() {
        String s = "";
        return s;
    }

    public void train(Entry entry) {
        double[] inputs = entry.inputs();
        double[] targetValues = entry.targets();

        double[] hiddenValues = hidden.calculateValues(inputs);
        double[] outputValues = output.calculateValues(hiddenValues);


        // Loop em perceptrons da camada de saida
        for (int i = 0; i < output.perceptrons.length; i++) {
            Perceptron p = output.perceptrons[i];
            double x = outputValues[i];
            // Loop em todos os pesos
            for (int j = 0; j < p.weights.length; j++) {
                // learningRate * gradiente * x
                double T = MultilayerPerceptron.learningRate * p.calculateGradient(outputValues, outputValues, targetValues) * x;
                p.weights[j] = p.weights[j] + T;
                System.out.println("Ajustei " + p.weights[j]);
            }
        }
        for (int i = 0; i < hidden.perceptrons.length; i++) {
            Perceptron p = hidden.perceptrons[i];
            double x = hiddenValues[i];
            // Loop em todos os pesos
            for (int j = 0; j < p.weights.length; j++) {
                // learningRate * gradiente * x
                double T = MultilayerPerceptron.learningRate * p.calculateGradient(outputValues, hiddenValues, targetValues) * x;
                p.weights[j] = p.weights[j] + T;
            }
            p.biasWeight = MultilayerPerceptron.learningRate * p.calculateGradient(outputValues, hiddenValues, targetValues) * p.biasWeight;

        }


    }


    public static void run() {
        MultilayerPerceptron rede = new MultilayerPerceptron(10, 1, 2);
        EntryParser entries = new EntryParser(100);
        for (Entry e : entries.learningData()) {
            rede.train(e);
        }
        for (Entry e : entries.testData()) {
            double[] output = rede.feedForward(e.inputs());
            System.out.println("outputs= " + Arrays.toString(output) + " espera=" + Arrays.toString(e.targets()));
        }

    }


}
