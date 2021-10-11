package dev.feldmann.autowordsuggestor;

import java.io.*;
import java.nio.charset.StandardCharsets;
import java.util.Properties;

public class Database {

    Properties properties;

    public Database() {
        properties = new Properties();
        try {
            properties.load(new BufferedReader(new InputStreamReader(new FileInputStream(getFile()), StandardCharsets.UTF_8)));
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public void save() {
        try {
            properties.store(new FileOutputStream(getFile()), "");
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public void reset() {
        properties.clear();
        save();
    }

    public File getFile() {
        File f = new File("weights.properties");
        if (!f.exists()) {
            try {
                f.createNewFile();
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
        return f;


    }

    public int getWeight(String palavra) {
        String pro = properties.getProperty(palavra);
        if (pro == null) {
            return 0;
        }
        try {
            return Integer.valueOf(pro);
        } catch (NumberFormatException ex) {
            return 0;
        }
    }

    public void setWeight(String palavra, int weight) {
        properties.setProperty(palavra, weight + "");
    }
}
