package dev.feldmann.aed2.pathfinding;

public abstract class Cmd {

    String cmd;
    String desc;
    String usage;

    public Cmd(String cmd, String desc, String usage) {
        this.cmd = cmd;
        this.usage = usage;
        this.desc = desc;
    }

    public void sendUsage() {
        System.out.println("Uso incorreto! ");
        System.out.println(cmd + " " + usage);
    }

    public abstract void execute(String[] args, Graph gr);
}

