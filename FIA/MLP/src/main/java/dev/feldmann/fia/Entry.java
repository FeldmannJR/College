package dev.feldmann.fia;

public class Entry {

    int id = 0;
    double inputs[] = new double[9];
    int target;
    boolean fail = false;

    public Entry(String str) {
        String[] valores = str.split(",");
        try {
            if (valores.length == 11) {
                this.id = Integer.parseInt(valores[0]);
                this.target = normalizeClass(Integer.parseInt(valores[10]));
                for (int x = 1; x <= 9; x++) {
                    int valor = Integer.parseInt(valores[x]);
                    this.inputs[x - 1] = normalizeInput(valor);
                }
            }
        } catch (Exception ex) {
            fail = true;
        }

    }

    public double[] inputs() {
        return inputs;
    }

    public int getTarget() {
        return target;
    }

    public double[] targets() {

        return new double[]{
                target == 0 ? 1 : 0,
                target == 1 ? 1 : 0
        };
    }

    public double normalizeInput(int valor) {
        int min = 1;
        int max = 10;
        return (double) (valor - min) / (max - min);
    }

    public int normalizeClass(int valor) {
        return valor == 2 ? -1 : 1;
    }

    public String print() {
        String s = "[";
        s += '[';
        for (double input : inputs) {
            s = s + (int) input + ",";
        }
        s = s.substring(0, s.length() - 1);
        s += "],";
        s += '[';
        s += target;
        s += ']';
        s += "],";
        return s;
    }


}
