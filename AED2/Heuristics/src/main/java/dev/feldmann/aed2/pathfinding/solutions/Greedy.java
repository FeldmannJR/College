package dev.feldmann.aed2.pathfinding.solutions;

import dev.feldmann.aed2.pathfinding.Solution;
import dev.feldmann.aed2.pathfinding.Graph;
import dev.feldmann.aed2.pathfinding.Edge;
import dev.feldmann.aed2.pathfinding.Node;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

public class Greedy extends Solution {


    public Greedy(Graph graph) {
        super(graph);
    }

    @Override
    public List<Node> calculatePath(Node start, Node end) {
        HashMap<Character, Node> nodes = this.getGraph().getNodes();
        List<Node> unvisited = new ArrayList<>(nodes.values());
        // Seta todos os nodos como não visitados
        for (Node n : nodes.values()) {
            if (n == start)
                n.setDistance(0);
            else {
                n.setDistance(Integer.MAX_VALUE);
                unvisited.add(n);
            }
        }

        List<Node> path = new ArrayList<>();
        Node current = start;
        int distance = 0;
        path.add(start);
        while (true) {
            Edge closest = closest(current);
            // Não chegou em lugar nenhum
            if (closest == null) {
                System.out.println("Não chegou no nodo desejado!");
                break;
            }
            distance += closest.getWeight();
            // Achou o final
            if (closest.getEnd() == end) {
                path.add(end);
                break;
            }
            // Vai pro mais perto
            closest.getEnd().setVisited(true);
            path.add(closest.getEnd());
            current = closest.getEnd();
        }
        getGraph().setLastDistance(distance);
        return path;
    }

    public Edge closest(Node n) {
        Edge closest = null;
        for (Edge aresta : getGraph().getNeighbours(n)) {
            if (!aresta.getEnd().isVisited()) {
                if (closest == null || closest.getWeight() > aresta.getWeight()) {
                    closest = aresta;
                }
            }
        }
        return closest;
    }

}
