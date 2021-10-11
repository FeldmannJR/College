package dev.feldmann.aed2.pathfinding.cmds;

import dev.feldmann.aed2.pathfinding.Cmd;
import dev.feldmann.aed2.pathfinding.Graph;

public class Exit extends Cmd {
    public Exit() {
        super("sair", "Sai do programa", "");
    }

    @Override
    public void execute(String[] args, Graph gr) {
        System.exit(1);
    }
}
