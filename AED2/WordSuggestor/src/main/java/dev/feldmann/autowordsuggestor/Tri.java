package dev.feldmann.autowordsuggestor;

import java.io.*;
import java.nio.charset.StandardCharsets;
import java.util.*;

public class Tri {

    Nodo root = new Nodo(' ');
    private Database weights = new Database();

    public void addWord(String s) {
        s = s.toLowerCase();
        root.addWord(s, weights.getWeight(s));
    }

    public void addWeight(String palavra) {
        Nodo nodo = root.getNodo(palavra);
        if (nodo != null) {
            nodo.weight++;
            weights.setWeight(palavra, nodo.getWeight());
            weights.save();
        }
    }

    public boolean hasWord(String s) {
        s = s.toLowerCase();
        return root.hasWord(s);
    }

    public List<String> suggest(String palavra, int max) {
        palavra = palavra.toLowerCase();
        Nodo nodo = palavra.isEmpty() ? root : root.getNodo(palavra);
        HashMap<String, Integer> suggestions = new HashMap<>();
        if (nodo == null) return new ArrayList<>();
        nodo.suggest("", suggestions);
        List<String> sugs = new ArrayList<>(suggestions.keySet());
        sugs.sort((o1, o2) -> suggestions.get(o2) - suggestions.get(o1));
        return sugs.subList(0, Math.min(max, sugs.size()));
    }

    public void loadFromStream(InputStream stream) {

        Scanner sc = new Scanner(new BufferedReader(new InputStreamReader(stream)));
        int total = 0;
        while (sc.hasNextLine()) {
            String line = sc.nextLine();
            addWord(line);
            total++;
        }
        System.out.println("Li " + total);

    }

    public void resetWeights() {
        root.resetWeight();
        this.weights.reset();
    }
}
