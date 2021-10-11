package dev.feldmann.aed2.pathfinding;

public class Node implements Comparable<Node> {


    private boolean visited = false;
    private int distance = 0;
    private Node parent;
    private char id;

    public Node(char id) {
        this.id = id;
    }

    public char getId() {
        return id;
    }

    public void setParent(Node parent) {
        this.parent = parent;
    }

    public Node getParent() {
        return parent;
    }

    public void setDistance(int distance) {
        this.distance = distance;
    }

    public int getDistance() {
        return distance;
    }

    public boolean isVisited() {
        return visited;
    }

    public void setVisited(boolean visited) {
        this.visited = visited;
    }


    @Override
    public String toString() {
        return id + "";
    }

    @Override
    public int compareTo(Node o) {
        return o.distance - this.distance;
    }
}
