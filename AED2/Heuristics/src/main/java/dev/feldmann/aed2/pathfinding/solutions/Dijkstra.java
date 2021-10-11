package dev.feldmann.aed2.pathfinding.solutions;

import dev.feldmann.aed2.pathfinding.Solution;
import dev.feldmann.aed2.pathfinding.Graph;
import dev.feldmann.aed2.pathfinding.Edge;
import dev.feldmann.aed2.pathfinding.Node;

import java.util.*;

public class Dijkstra extends Solution {


    public Dijkstra(Graph graph) {
        super(graph);
    }

    public List<Node> calculatePath(Node start, Node end) {
        HashMap<Character, Node> nodes = this.getGraph().getNodes();
        List<Node> unvisited = new ArrayList<>(nodes.values());
        for (Node n : nodes.values()) {
            if (n == start)
                n.setDistance(0);
            else {
                n.setDistance(Integer.MAX_VALUE);
                unvisited.add(n);
            }
        }

        List<Node> path = new ArrayList<>();
        Node current;
        while (!unvisited.isEmpty()) {
            current = unvisited.get(0);
            for (Edge l : getGraph().getNeighbours(current)) {
                Node e = l.getEnd();
                if (!e.isVisited()) {
                    if (e.getDistance() > (current.getDistance() + l.getWeight())) {
                        e.setDistance(current.getDistance() + l.getWeight());
                        e.setParent(current);

                        if (e == end) {
                            getGraph().setLastDistance(e.getDistance());
                            path.clear();
                            Node n = e;
                            path.add(e);
                            do {
                                n = n.getParent();
                                path.add(n);
                            } while (n.getParent() != null);

                        }
                    }
                }
            }
            unvisited.remove(current);
            current.setVisited(true);

        }
        Collections.reverse(path);
        return path;
    }

}
