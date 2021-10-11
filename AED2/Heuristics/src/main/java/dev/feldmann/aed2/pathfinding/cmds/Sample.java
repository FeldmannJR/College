package dev.feldmann.aed2.pathfinding.cmds;

import dev.feldmann.aed2.pathfinding.Graph;
import dev.feldmann.aed2.pathfinding.Cmd;

public class Sample extends Cmd {

    public Sample() {
        super("sample", "adiciona nodos e arestas de exemplo", "");
    }

    @Override
    public void execute(String[] args, Graph gr) {
        gr.addNode('a', 'b', 'c', 'd', 'e');
        gr.setEdge('a', 'b', 3);
        gr.setEdge('a', 'c', 7);
        gr.setEdge('c', 'd', 5);
        gr.setEdge('b', 'd', 10);
        gr.setEdge('d', 'e', 8);
        gr.setEdge('c', 'e', 14);
        System.out.println("Adicionado nodos de exemplo!");
    }
}
