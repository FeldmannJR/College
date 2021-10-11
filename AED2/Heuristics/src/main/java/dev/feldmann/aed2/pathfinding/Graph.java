package dev.feldmann.aed2.pathfinding;

import dev.feldmann.aed2.pathfinding.solutions.Dijkstra;
import dev.feldmann.aed2.pathfinding.solutions.Greedy;

import java.util.ArrayList;
import java.util.Comparator;
import java.util.HashMap;
import java.util.List;
import java.util.stream.Collectors;

public class Graph {
    // A distancia calculada entre os nodos
    public int lastDistance = 0;
    // Nodos
    public HashMap<Character, Node> nodes = new HashMap<>();
    // Arestas
    protected List<Edge> edges = new ArrayList<>();

    private Dijkstra dijkstra;
    private Greedy greedy;

    public Graph() {
        this.dijkstra = new Dijkstra(this);
        this.greedy = new Greedy(this);
    }

    public int getLastDistance() {
        return lastDistance;
    }

    public void addNode(char... chars) {
        for (char c : chars)
            nodes.put(c, new Node(c));
    }

    public void setEdge(char start, char end, int weight) {
        edges.add(new Edge(nodes.get(start), nodes.get(end), weight));
    }

    public Dijkstra getDijkstra() {
        return dijkstra;
    }

    public Greedy getGreedy() {
        return greedy;
    }


    public List<Edge> getNeighbours(Node node) {
        return edges.stream().filter((l) -> l.getStart() == node).sorted(Comparator.comparingInt(Edge::getWeight)).collect(Collectors.toList());
    }

    public void reset() {
        lastDistance = 0;
        nodes.clear();
        edges.clear();
    }

    public void resetFlags() {
        for (Node n : nodes.values()) {
            n.setVisited(false);
            n.setDistance(0);
            n.setParent(null);
        }
    }

    public HashMap<Character, Node> getNodes() {
        return nodes;
    }

    public void setLastDistance(int lastDistance) {
        this.lastDistance = lastDistance;
    }

}
