package dev.feldmann.aed2.pathfinding.cmds;

import dev.feldmann.aed2.pathfinding.Solution;
import dev.feldmann.aed2.pathfinding.Cmd;
import dev.feldmann.aed2.pathfinding.Graph;
import dev.feldmann.aed2.pathfinding.Node;

import java.util.List;

public class Search extends Cmd {
    public Search() {
        super("search", "procura caminho do nodo A para o nodo B", "NodoA NodoB [Greedy]");
    }

    @Override
    public void execute(String[] args, Graph gr) {
        if (args.length < 2 || args[0].length() != 1 || args[1].length() != 1) {
            sendUsage();
            return;
        }
        Solution sol = gr.getDijkstra();
        if (args.length == 3 && args[2].equalsIgnoreCase("greedy")) {
            sol = gr.getGreedy();
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
        List<Node> path = sol.calculatePath(a, b);
        System.out.println("Calculando caminho de " + a + " para " + b + " com o algoritmo " + sol.getClass().getSimpleName() + "!");
        for (int x = 0; x < path.size(); x++) {
            Node n = path.get(x);
            if (x != 0) {
                System.out.print(" -> ");
            }
            System.out.print(n.getId());
        }
        System.out.println("\nCom custo de " + gr.getLastDistance());
        gr.resetFlags();
    }

}
