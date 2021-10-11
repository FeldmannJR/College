package dev.feldmann.autowordsuggestor;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

public class Nodo {

    // Escolhi não usar maps/trees do java por que já faz a indexação automatico
    char c;
    Nodo[] nodos = new Nodo[26 + 12];
    boolean last;
    int weight;

    public Nodo(char ch) {
        this.c = ch;
    }

    public int getWeight() {
        return weight;
    }

    public boolean isLast() {
        return last;
    }

    public void suggest(String prefix, HashMap<String, Integer> map) {
        if (this.last) {
            map.put(prefix, weight);
        }
        for (Nodo n : nodos) {
            if (n != null) {
                n.suggest(prefix + n.c, map);
            }
        }
    }

    public Nodo getNodo(String prefix) {
        if (prefix.isEmpty()) {
            return this;
        }
        char atual = prefix.charAt(0);
        prefix = prefix.substring(1);
        int code = convertCharToInt(atual);
        if (hasChar(code)) {
            return nodos[code].getNodo(prefix);
        }
        return null;
    }

    public boolean hasChar(int ch) {
        return nodos[ch] != null;
    }

    public void resetWeight() {
        this.weight = 0;
        for (Nodo n : nodos) {
            if (n != null) n.resetWeight();
        }
    }

    public void addWord(String s, int weight) {
        if (s.isEmpty()) {
            this.last = true;
            this.weight = weight;
            return;
        }
        char atual = s.charAt(0);
        s = s.substring(1);
        Nodo node = getOrCreate(atual);
        node.addWord(s, weight);
    }

    public Nodo getOrCreate(char ch) {
        int nodo = convertCharToInt(ch);
        if (this.nodos[nodo] == null) {
            this.nodos[nodo] = new Nodo(ch);
        }
        return this.nodos[nodo];
    }

    public boolean hasWord(String s) {
        if (s.isEmpty()) {
            return last;
        }
        char atual = s.charAt(0);
        s = s.substring(1);
        int code = convertCharToInt(atual);
        if (hasChar(code)) {
            return this.nodos[code].hasWord(s);
        }
        return false;
    }

    public Integer convertCharToInt(char c) {
        int end = 'z' - 'a';
        if (c == 'â') return end + 1;
        if (c == 'ã') return end + 2;
        if (c == 'á') return end + 3;
        if (c == 'à') return end + 4;
        if (c == 'é') return end + 5;
        if (c == 'ê') return end + 6;
        if (c == 'í') return end + 7;
        if (c == 'õ') return end + 8;
        if (c == 'ó') return end + 9;
        if (c == 'ô') return end + 10;
        if (c == 'ú') return end + 11;
        if (c == 'ç') return end + 12;
        if (c == 'ü') c = 'u';
        if (c < 97) return null;
        if (c > 'z') return null;
        return c - 97;
    }


}
