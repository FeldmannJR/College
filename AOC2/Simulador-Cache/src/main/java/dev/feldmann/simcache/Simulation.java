package dev.feldmann.simcache;

import java.io.*;
import java.util.ArrayList;
import java.util.List;
import java.util.stream.Collectors;

public class Simulation {

    private SimCacheConfig config;
    private File inputFile;
    // Java não suporta unsigned, como vou fazer operações com 32bits vou precisar de um long(64bits)
    private List<Long> addresses;
    private SimSet[] sets;


    public Simulation(SimCacheConfig config, File inputFile) {
        this.config = config;
        this.inputFile = inputFile;
        this.addresses = this.parseAddresses();
        this.sets = new SimSet[this.config.getNsets()];
        for (int i = 0; i < sets.length; i++) {
            this.sets[i] = new SimSet(i, this.config);
        }
    }

    public List<Long> parseAddresses() {
        try {
            List<Long> addresses = new ArrayList<>();
            BufferedInputStream buffer = new BufferedInputStream(new FileInputStream(inputFile));
            DataInputStream stream = new DataInputStream(buffer);
            while (true) {
                try {
                    int address = stream.readInt();

                    addresses.add(Integer.toUnsignedLong(address));
                } catch (EOFException e) {
                    // Terminou de ler o arquivo
                    break;
                }
            }
            return addresses;
        } catch (IOException ex) {
            ex.printStackTrace();
            return null;
        }
    }

    public void simulate() {
        int hits = 0;
        int missCapacidade = 0;
        int missCompulsorio = 0;
        int missConflito = 0;
        int total = 0;
        for (Long address : addresses) {
            total++;
            MissType miss = accessAddress(address);
            if (miss == null) {
                hits++;
                continue;
            }
            switch (miss) {
                case COMPULSORIO:
                    missCompulsorio++;
                    break;
                case CAPACIDADE:
                    missCapacidade++;
                    break;
                case CONFLITO:
                    missConflito++;
                    break;
            }

        }
        int miss = missCompulsorio + missCapacidade + missConflito;
        double taxaMiss = ((double) (miss) / total);
        double taxaMissCompulsorio = ((double) missCompulsorio / miss);
        double taxaMissCapacidade = ((double) missCapacidade / miss);
        double taxaMissConflito = ((double) missConflito / miss);
        double taxaHit = 1 - taxaMiss;
        if (this.config.isOutputType()) {
            System.out.printf("%d, %.2f, %.2f, %.2f, %.2f, %.2f%n", total, taxaHit, taxaMiss, taxaMissCompulsorio, taxaMissCapacidade, taxaMissConflito);
        } else {
            if (config.useHistory()) {
                printDistinct(addresses);
                for (SimSet set : sets) {
                    set.printHistory();
                }
            }
            System.out.println(
                    "NSETS: " + config.getNsets() +
                            "\nBSIZE: " + config.getBsize() +
                            "\nASSOC: " + config.getAssoc() +
                            "\nREPLACE: " + config.getRepl().getName() +
                            "\nSIZE(TAG): " + config.getTagSize() +
                            "\nSIZE(INDICE):" + config.getIndexSize());
            System.out.printf("Total" +
                            "\tHit" +
                            "\tMiss" +
                            "\tComp" +
                            "\t%%" +
                            "\tCapa" +
                            "\t%%" +
                            "\tCon" +
                            "\t%%" +
                            "%n" +

                            "%d\t" + //Total
                            "%.2f\t" + // Hit
                            "%.2f\t" + // miss
                            "%d\t" + //comp
                            "%.2f\t" + //rate comp
                            "%d\t" + // capa
                            "%.2f\t" + // rate capa
                            "%d\t" + // conflito
                            "%.2f" + // rate conflito
                            "%n",
                    total,
                    taxaHit,
                    taxaMiss,
                    missCompulsorio,
                    taxaMissCompulsorio,
                    missCapacidade,
                    taxaMissCapacidade,
                    missConflito,
                    taxaMissConflito
            );
        }

    }

    public MissType accessAddress(long address) {
        int index = config.getIndex(address);
        MissType miss = sets[index].read(address);
        // Se teve miss escreve na cache
        if (miss != null) {
            sets[index].write(address);
        }
        if (miss == MissType.CAPACIDADE) {
            if (!isFull()) {
                miss = MissType.CONFLITO;
            }
        }
//        if (SimCache.DEBUG) {
//            System.out.println("Endereço " + address + " 0x" + Integer.toUnsignedString(address, 16) +
//                    " Tag: " + config.getTag(address) +
//                    " Indice: " + config.getIndex(address) +
//                    " Miss:" + (miss == null ? "Nop" : miss.name()));
//
//            System.out.println(Utils.toBinary(address)+"\tAddress");
//            System.out.println(Utils.toBinary(index)+"\tIndex");
//            System.out.println(Utils.toBinary(config.getTag(address))+"\tTag");
//        }
        return miss;
    }

    public void printDistinct(List<Long> address) {
        List<Long> blocos = new ArrayList<>();
        List<Long> distinct = address.stream().distinct().sorted().collect(Collectors.toList());
        for (Long val : distinct) {
            blocos.add(config.getBlockAddress(val));
        }
        blocos = blocos.stream().distinct().collect(Collectors.toList());
        System.out.println("Endereços Únicos(" + distinct.size() + "): " + Utils.listToString(distinct));
        System.out.println("Blocos Únicos(" + blocos.size() + "): " + Utils.listToString(blocos));

    }


    public boolean isFull() {
        for (SimSet set : sets) {
            if (!set.isFull()) {
                return false;
            }
        }
        return true;
    }
}
