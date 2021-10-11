package dev.feldmann.autowordsuggestor;

import java.io.InputStream;

public class Main {

    public static void main(String[] args) {
        Tri tri = new Tri();
        tri.loadFromStream(getLanguageStream());
        MainScreen.main(tri);
    }

    private static InputStream getLanguageStream() {
        return Main.class.getResourceAsStream("/brazilian.txt");
    }
}
