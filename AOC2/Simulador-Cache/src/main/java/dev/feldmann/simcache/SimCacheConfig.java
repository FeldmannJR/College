package dev.feldmann.simcache;

import java.util.Random;

public class SimCacheConfig {

    private Random random;
    /**
     * Quantos bits vão para o offset
     */
    private int offsetBits = 2;
    /**
     * Número de conjutos
     */
    private int nsets;
    /**
     * Tamanho do bloco
     */
    private int bsize;
    /**
     * Grau de associatividade
     */
    private int assoc;
    /**
     * Política de Substituição
     */
    private ReplacementPolicy repl;
    /**
     * Flag de saída
     * true = Saida padrão para o professor parsear
     * false = Formato Livre
     */
    private boolean outputType;
    private boolean debug;
    private boolean history;

    public SimCacheConfig(int nsets, int bsize, int assoc, ReplacementPolicy repl, boolean outputType) {
        this.nsets = nsets;
        this.bsize = bsize;
        this.assoc = assoc;
        this.repl = repl;
        this.outputType = outputType;
        this.offsetBits = (int) (Math.log(bsize) / Math.log(2));
        // Se precisar da pra fixar a seed aqui
        this.random = new Random();
    }

    public int getAssoc() {
        return assoc;
    }

    public int getBsize() {
        return bsize;
    }

    public int getNsets() {
        return nsets;
    }

    public ReplacementPolicy getRepl() {
        return repl;
    }

    public boolean isOutputType() {
        return outputType;
    }

    public int getIndexSize() {
        return (int) (Math.log(getNsets()) / Math.log(2));
    }


    public int getTagSize() {
        return 32 - getIndexSize() - offsetBits;
    }

    public int getTag(long address) {
        // Quantidade de bits que precisa pra tag
        int n = getTagSize();
        // Desloca para a direita, e aplica um mask para só pegar os n bits da direita
        int shiftAmount = getIndexSize() + offsetBits;
        // Precisa ser long, pois se o n for 32 estoura a representatividade do integer
        long mask = (long) (Math.pow(2, n) - 1);
        address = address >> (shiftAmount);
        address = address & (mask);
        return (int) address;
    }

    public int getIndex(long address) {
        return (int) ((address >> offsetBits) & ((long) Math.pow(2, getIndexSize()) - 1));
    }

    public Random getRandom() {
        return random;
    }

    public void setDebug(boolean debug) {
        this.debug = debug;
    }

    public boolean isDebug() {
        return debug;
    }

    public void setHistory(boolean history) {
        this.history = history;
    }

    public int getOffsetBits() {
        return offsetBits;
    }

    public boolean useHistory() {
        return history;
    }

    public Long getBlockAddress(Long address) {
        return (address >> getOffsetBits() & ((long) Math.pow(2, 32 - getOffsetBits()) - 1));
    }
}
