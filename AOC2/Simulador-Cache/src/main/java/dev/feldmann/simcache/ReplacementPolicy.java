package dev.feldmann.simcache;

public enum ReplacementPolicy {
    R("Random"),
    F("FIFO"),
    L("LRU");
    private String name;

    ReplacementPolicy(String name) {
        this.name = name;
    }

    public String getName() {
        return name;
    }
}
