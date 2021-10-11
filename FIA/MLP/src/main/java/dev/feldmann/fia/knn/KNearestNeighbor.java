package dev.feldmann.fia.knn;

import dev.feldmann.fia.Entry;
import dev.feldmann.fia.EntryParser;

import java.util.ArrayList;
import java.util.Comparator;
import java.util.HashMap;
import java.util.List;

public class KNearestNeighbor {

    private int k;

    List<Entry> classified;


    public KNearestNeighbor(int k, List<Entry> classified) {
        this.classified = classified;
        this.k = k;
    }


    public int classificate(Entry entry) {
        // Calcula a distancia para todos os pontos do grafico
        HashMap<Entry, Double> distances = new HashMap<>();
        for (Entry n : classified) {
            double distance = calculateDistance(entry, n);
            distances.put(n, distance);
        }
        // Ordena para pegar o mais perto
        List<Entry> entries = new ArrayList<>(classified);
        entries.sort((o1, o2) -> {
            double dif = distances.get(o1) - distances.get(o2);
            if (dif < 0) {
                return -1;
            }
            if (dif > 0) {
                return 1;
            }
            return 0;
        });
        // Faz uma somatória para ver qual está mais perto(o dado está normalizado, sendo -1 benign e 1 benign)
        int tipos = 0;
        for (int x = 0; x < k; x++) {
            tipos += entries.get(x).getTarget();
        }
        return tipos > 0 ? 1 : -1;
    }


    public double calculateDistance(Entry e1, Entry e2) {
        double sum = 0;
        double[] i1 = e1.inputs();
        double[] i2 = e2.inputs();
        for (int i = 0; i < i1.length; i++) {
            sum += Math.pow(i1[i] - i2[i], 2);
        }
        return Math.sqrt(sum);

    }

    public static void run() {

        int total = 0;
        int hits = 0;
        int epochs = 1000;
        double squareError = 0;

        for (int i = 0; i < epochs; i++) {
            // A seed para randomizar as entradas é sempre a mesma baseada na epoca para ter consistencia no teste
            EntryParser parser = new EntryParser(100, i * 153);
            KNearestNeighbor k = new KNearestNeighbor(9, parser.learningData());
            // Total de casos testados
            int epochTotal = 0;
            // Total de acertos
            int epochHits = 0;
            for (Entry testDatum : parser.testData()) {
                if (k.classificate(testDatum) == testDatum.getTarget()) {
                    epochHits++;
                }
                epochTotal++;
            }
            total += epochTotal;
            hits += epochHits;
            squareError += Math.pow(epochTotal - epochHits, 2);
        }
        System.out.println("Acerto Médio: " + (double) hits / total);
        System.out.println("Erro Médio: " + ((double) total - hits) / epochs);
        System.out.println("Erro Médio Quadratico: " + (squareError / epochs));
    }
}
