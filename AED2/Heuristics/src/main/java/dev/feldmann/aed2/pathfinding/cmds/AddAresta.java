package dev.feldmann.aed2.pathfinding.cmds;

import dev.feldmann.aed2.pathfinding.Cmd;
import dev.feldmann.aed2.pathfinding.Graph;

public class AddAresta extends Cmd {
    public AddAresta() {
        super("aresta", "adiciona uma aresta ao grafo", "NodoA NodoB peso");
    }

    @Override
    public void execute(String[] args, Graph gr) {
        if (args.length != 3 || args[0].length() != 1 || args[1].length() != 1) {
            sendUsage();
            return;
        }
        int peso;
        try {
            peso = Integer.valueOf(args[2]);
        } catch (NumberFormatException ex) {
            sendUsage();
            return;
        }
        char a = args[0].charAt(0);
        char b = args[1].charAt(0);
        if (!gr.nodes.containsKey(a)) {
            System.out.println("Nodo a não está no grafo");
            return;
        }
        if (!gr.nodes.containsKey(b)) {
            System.out.println("Nodo b não está no grafo");
            return;
        }
        gr.setEdge(a, b, peso);

        System.out.println("Adicionada aresta enre o nodo " + a + " e " + b + " com o peso de " + peso + " !");
    }
}
