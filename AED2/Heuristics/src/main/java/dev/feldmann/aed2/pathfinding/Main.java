package dev.feldmann.aed2.pathfinding;


import dev.feldmann.aed2.pathfinding.cmds.*;

import java.util.HashMap;
import java.util.Scanner;

public class Main {


    private static HashMap<String, Cmd> commands = new HashMap<>();
    public static Graph graph = new Graph();


    private static void registerCmds() {
        registerCmd(new AddAresta());
        registerCmd(new AddNodo());
        registerCmd(new Search());
        registerCmd(new Reset());
        registerCmd(new Exit());
        registerCmd(new Sample());

    }

    private static void registerCmd(Cmd cmd) {
        commands.put(cmd.cmd, cmd);
    }


    private static void showHelp() {
        for (Cmd cmd : commands.values()) {
            System.out.println(cmd.cmd + " " + cmd.usage + " - " + cmd.desc);
        }
        System.out.println("Exemplo Procuras:");
        System.out.println("'search a d' = irá procurar caminho do a ao d usando Dijkstra");
        System.out.println("'search b e greedy' = irá procurar caminho do b ao e usando o algoritmo guloso");
    }

    private static boolean handleCmd(String cmd, String[] args) {
        if (!commands.containsKey(cmd.toLowerCase())) {
            System.out.println("Comando '" + cmd + "' não encontrado!");
            System.out.println("Comandos válidos:");
            showHelp();
            return true;
        }
        commands.get(cmd.toLowerCase()).execute(args, graph);

        return true;
    }

    public static void main(String[] args) {
        registerCmds();
        System.out.println("Digite um dos comandos validos: ");
        showHelp();
        Scanner sc = new Scanner(System.in);
        while (sc.hasNext()) {
            String line = sc.nextLine().trim();
            if (line.isEmpty()) {
                continue;
            }
            String[] split = line.split(" ");
            String cmd = line;
            if (split.length > 0) {
                cmd = split[0];
            }
            String[] cmdargs = new String[Math.max(0, split.length - 1)];

            for (int x = 1; x < split.length; x++) {
                cmdargs[x - 1] = split[x];
            }
            if (!handleCmd(cmd, cmdargs)) {
                break;
            }
        }

    }
}
