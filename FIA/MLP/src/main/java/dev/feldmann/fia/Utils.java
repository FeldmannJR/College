package dev.feldmann.fia;


import java.util.Random;
import java.util.function.BiFunction;
import java.util.function.Function;

public class Utils {
    public static double[] generateRandomWeights(int size, double min, double max) {
        return generateRandomWeights(new Random(), size, min, max);
    }

    public static double[] generateRandomWeights(Random r, int size, double min, double max) {
        double[] d = new double[size];
        for (int i = 0; i < size; i++) {
            d[i] = min + (r.nextDouble() * (max - min));
        }
        return d;
    }

    public static double[] sub(double[] array, double[] anotherArray) {
        return forEach(array, anotherArray, (x, y) -> x - y);
    }

    public static double[] forEach(double[] array, double[] anotherArray, BiFunction<Double, Double, Double> func) {
        double[] result = new double[anotherArray.length];
        for (int x = 0; x < array.length; x++) {
            result[x] = func.apply(array[x], anotherArray[x]);
        }
        return result;
    }

    public static double[] forEach(double[] array, Function<Double, Double> func) {
        double[] result = new double[array.length];
        for (int x = 0; x < array.length; x++) {
            result[x] = func.apply(array[x]);
        }
        return result;
    }

    public static Double sigmoid(Double x) {
        return 1.0 / (1 + Math.exp(-x));
    }


}
