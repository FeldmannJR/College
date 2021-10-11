package dev.feldmann.simcache;

import java.util.ArrayList;

public class SimSet {

    private int numSet;
    private long timeCount = 0;
    private SimCacheConfig config;

    private int[] tags;
    private boolean[] valids;
    private long[] times = null;
    private ArrayList<Long>[] history;

    public SimSet(int numSet, SimCacheConfig config) {
        this.numSet = numSet;
        this.config = config;
        this.tags = new int[config.getAssoc()];
        this.valids = new boolean[config.getAssoc()];
        if (this.config.getRepl() != ReplacementPolicy.R) {
            this.times = new long[config.getAssoc()];
        }
        history = new ArrayList[config.getAssoc()];
        for (int i = 0; i < history.length; i++) {
            history[i] = new ArrayList<Long>();
        }
    }

    /**
     * @return MissType qual foi o miss que ocorreu, caso null não ocorreu miss
     */
    public MissType read(long address) {
        int tag = config.getTag(address);
        boolean compulsorio = false;
        for (int i = 0; i < tags.length; i++) {
            if (!valids[i]) {
                compulsorio = true;
            } else {
                if (tags[i] == tag) {
                    if (this.config.getRepl() == ReplacementPolicy.L) {
                        // Não usei currentMilliseconds , pois talvez pode acontecer de fazer 2 leituras no mesmo ms
                        this.times[i] = timeCount++;
                    }

                    if (config.isDebug()) {
                        System.out.println("HIT: " + address + "(TAG:" + tag + ") bloco " + numSet + " posição " + i);
                    }
                    return null;
                }
            }
        }
        return compulsorio ? MissType.COMPULSORIO : MissType.CAPACIDADE;
    }

    /**
     * Adiciona um endereço na cache
     *
     * @return posição em que foi colocado o endereço
     */
    public int write(long address) {
        int tag = config.getTag(address);
        for (int i = 0; i < tags.length; i++) {
            if (!valids[i]) {
                if (config.isDebug()) {
                    System.out.println("WRITE: " + address + " set:" + numSet + " slot:" + i);
                }
                write(address, tag, i);
                return i;
            }
        }
        int slot;
        if (config.getRepl() == ReplacementPolicy.R) {
            slot = getRandomSlot(tag);
        } else {
            slot = getTimeSlot(tag);
        }
        if (config.isDebug()) {
            System.out.println("OVERWRITE: " + address + " set:" + numSet + " slot:" + slot + " overwrite:" + this.tags[slot]);
        }
        write(address, tag, slot);
        return slot;
    }

    private void write(long address, int tag, int slot) {
        history[slot].add(address);
        valids[slot] = true;
        tags[slot] = tag;
        if (this.config.getRepl() != ReplacementPolicy.R) {
            this.times[slot] = timeCount++;
        }
    }

    public void printHistory() {
        System.out.println("====== History Set " + numSet + "(BLOCK) ======");
        for (int i = 0; i < tags.length; i++) {
            StringBuilder historyString = new StringBuilder();
            for (Long val : history[i]) {
                if (historyString.length() > 0) {
                    historyString.append(", ");
                }
                historyString.append(config.getBlockAddress(val));
            }
            System.out.printf("|%d| %s%n", i, historyString.toString());
        }
        System.out.println();
    }

    private int getRandomSlot(int tag) {
        int i = config.getRandom().nextInt(this.tags.length);
        return i;
    }

    private int getTimeSlot(int tag) {
        long minTime = -1;
        int minSlot = -1;
        // Procura o tempo de escrita/acesso mais antigo
        // Funciona tanto pra LRU/FIFO
        for (int i = 0; i < tags.length; i++) {
            if (minTime == -1 || times[i] < minTime) {
                minTime = times[i];
                minSlot = i;
            }
        }
        return minSlot;
    }

    public boolean isFull() {
        for (boolean valid : valids) {
            if (!valid) {
                return false;
            }
        }
        return true;
    }


}
