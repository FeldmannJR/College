package dev.feldmann.fia;

import java.io.File;
import java.io.FileNotFoundException;
import java.net.URL;
import java.util.*;

public class EntryParser {
    private List<Entry> data;
    private Random shuffler;

    private int testDataQuantity;

    public EntryParser(int testDataQuantity) {
        this(testDataQuantity, null);
    }

    public EntryParser(int testDataQuantity, int seed) {
        this(testDataQuantity, new Random(seed));
    }

    public EntryParser(int testDataQuantity, Random rnd) {
        this.testDataQuantity = testDataQuantity;
        this.shuffler = rnd;

    }

    public Entry first() {
        return parseData().get(0);
    }

    public List<Entry> parseData() {
        if (this.data == null) {
            List<Entry> data = new ArrayList<Entry>();
            File f = getFileFromResources("breast-cancer-wisconsin.data");
            try {

                Scanner s = new Scanner(f);
                while (s.hasNextLine()) {
                    String line = s.nextLine();
                    data.add(new Entry(line));
                }
            } catch (FileNotFoundException e) {
                e.printStackTrace();
            }
            this.data = data;
            if (shuffler != null) {
                Collections.shuffle(this.data, shuffler);
            }
        }
        return this.data;
    }

    public List<Entry> testData() {
        return parseData().subList(0, testDataQuantity);
    }

    public List<Entry> learningData() {
        return parseData().subList(testDataQuantity, parseData().size());
    }

    // get file from classpath, resources folder
    private static File getFileFromResources(String fileName) {

        ClassLoader classLoader = EntryParser.class.getClassLoader();

        URL resource = classLoader.getResource(fileName);
        if (resource == null) {
            throw new IllegalArgumentException("file is not found!");
        } else {
            return new File(resource.getFile());
        }

    }
}
