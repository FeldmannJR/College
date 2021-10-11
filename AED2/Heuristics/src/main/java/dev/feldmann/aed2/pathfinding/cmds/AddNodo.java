package dev.feldmann.aed2.pathfinding.cmds;

import dev.feldmann.aed2.pathfinding.Graph;
import dev.feldmann.aed2.pathfinding.Cmd;

public class AddNodo extends Cmd {
    public AddNodo() {
        super("nodo", "adiciona um nodo ao grafo", "LETRA");
    }

    @Override
    public void execute(String[] args, Graph gr) {
        if (args.length != 1 || args[0].length() != 1) {
            sendUsage();
            return;
        }
        char c = args[0].charAt(0);
        if (gr.nodes.containsKey(c)) {
            System.out.println("Nodo já está no grafo!");
            return;
        }
        gr.addNode(c);
        System.out.println("Adicionado nodo " + c);
    }
}
