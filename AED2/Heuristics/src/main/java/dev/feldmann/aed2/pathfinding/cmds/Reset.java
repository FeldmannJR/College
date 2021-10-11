package dev.feldmann.aed2.pathfinding.cmds;

import dev.feldmann.aed2.pathfinding.Cmd;
import dev.feldmann.aed2.pathfinding.Graph;

public class Reset extends Cmd {
    public Reset() {
        super("reset", "remove todos os nodos e arestas do grafo", "");
    }

    @Override
    public void execute(String[] args, Graph gr) {
        gr.reset();
        System.out.println("Resetado!");
    }
}
