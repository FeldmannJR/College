package dev.feldmann.simcache;

import java.io.File;

public class SimCache {


    public static void main(String[] args) {
        //<nsets> <bsize> <assoc> <substituição> <flag_saida> arquivo_de_entrada
        if (args.length < 6) {
            showInvalidUsage();
            return;
        }
        int nsets;
        int bsize;
        int assoc;
        ReplacementPolicy replace;
        int flag;
        File file;
        try {
            nsets = validateInteger(args[0], "nsets");
            bsize = validateInteger(args[1], "bsize");
            assoc = validateInteger(args[2], "assoc");
            replace = validateReplacement(args[3]);
            flag = validateInteger(args[4], "flag_saida");
            file = validateInputFile(args[5]);
        } catch (IllegalArgumentException ex) {
            showInvalidUsage();
            return;
        }
        boolean debug = false;
        boolean history = false;
        for (int i = 6; i < args.length; i++) {
            if (args[i].equalsIgnoreCase("--debug")) {
                debug = true;
            }
            if (args[i].equalsIgnoreCase("--history")) {
                history = true;
            }
        }
        SimCacheConfig config = new SimCacheConfig(nsets, bsize, assoc, replace, flag == 1);
        config.setDebug(debug);
        config.setHistory(history);
        Simulation simulation = new Simulation(config, file);
        simulation.simulate();

    }

    private static ReplacementPolicy validateReplacement(String value) {
        for (ReplacementPolicy policy : ReplacementPolicy.values()) {
            if (policy.name().toLowerCase().equals(value.toLowerCase()) || policy.getName().toLowerCase().equals(value.toLowerCase())) {
                return policy;
            }
        }
        System.out.println("O valor para a política de substituição é inválido!");
        throw new IllegalArgumentException(value);
    }

    private static File validateInputFile(String value) {
        File f = new File(value);
        if (!f.exists()) {
            System.out.println("Arquivo " + value + " não encontrado");
            throw new IllegalArgumentException(value);
        }
        return f;
    }

    private static Integer validateInteger(String value, String campo) throws NumberFormatException {
        try {
            Integer integer = Integer.valueOf(value);
            return integer;
        } catch (NumberFormatException ex) {
            System.out.println("Argumento " + campo + " não é um número!");
            throw new NumberFormatException(value);
        }
    }

    private static void showInvalidUsage() {
        System.out.println("Uso inválido!");
        System.out.println("Use: java -jar SimuladorCache.jar <nsets> <bsize> <assoc> <substituição> <flag_saida> arquivo_de_entrada");
    }

}
