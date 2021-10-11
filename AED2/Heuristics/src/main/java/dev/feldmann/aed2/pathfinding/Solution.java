package dev.feldmann.aed2.pathfinding;

import java.util.List;

public abstract class Solution {
    Graph graph;

    public Solution(Graph graph) {
        this.graph = graph;
    }

    public Graph getGraph() {
        return graph;
    }

    public List<Node> calculatePath(char a, char b) {
        return calculatePath(getGraph().getNodes().get(a), getGraph().getNodes().get(b));
    }

    public abstract List<Node> calculatePath(Node start, Node end);

}
