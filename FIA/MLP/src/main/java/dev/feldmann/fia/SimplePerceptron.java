package dev.feldmann.fia;

import java.util.List;
import java.util.Random;

public class SimplePerceptron {

    private static final double learningStep = 2;

    double weights[];

    int bias;
    double biasWeight;

    public SimplePerceptron(double[] weights, int bias, double biasWeight) {
        this.weights = weights;
        this.bias = bias;
        this.biasWeight = biasWeight;
    }

    public int calculate(double[] entradas) {
        if (entradas.length != weights.length) {
            throw new RuntimeException("Entradas com tamanho diferente da lista de weights");
        }
        double somatorio = 0;
        for (int i = 0; i < weights.length; i++) {
            somatorio += weights[i] * entradas[i];
        }
        return degrau(somatorio + (bias * biasWeight));
    }

    public boolean test(double[] entradas, int saidaEsperada) {
        return calculate(entradas) == saidaEsperada;
    }

    public boolean train(double[] inputs, int target) {
        int result = calculate(inputs);
        int error = target - result;

        //Errrouuuu
        if (error != 0) {
            for (int i = 0; i < weights.length; i++) {
                weights[i] = weights[i] + learningStep * error * inputs[i];
            }
            biasWeight = biasWeight + learningStep * error + bias;
            return false;
        }
        return true;
    }

    public int degrau(double valor) {
        return valor < 0 ? -1 : 1;
    }

    public static void run() {
        int epochs = 1000;
        double squareError = 0;
        int hits = 0;
        int total = 0;

        SimplePerceptron p = new SimplePerceptron(Utils.generateRandomWeights(new Random(1111), 9, 0, 1), 1, 0);
        for (int x = 0; x < epochs; x++) {
            Random r = new Random(x * 42);
            EntryParser parser = new EntryParser(100, r);

            List<Entry> entries = parser.learningData();


            for (Entry entry : entries) {
                p.train(entry.inputs, entry.target);
            }

            int epochHit = 0;
            int epochTotal = 0;
            for (Entry entr : parser.testData()) {
                if (p.test(entr.inputs, entr.target)) {
                    epochHit++;
                }
                epochTotal++;
            }
            hits += epochHit;
            total += epochTotal;
            squareError += Math.pow((double) epochTotal - epochHit, 2);
            double pct = ((double) epochHit / epochTotal) * 100;
        }
        System.out.println("Erro Médio Quadratico: " + squareError / total);
        System.out.println("Pct Acerto: " + (double) hits / total);
    }
}
