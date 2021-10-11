package robotica.ufpel.matheusih;

import java.util.ArrayList;

import lejos.nxt.Button;
import robotica.ufpel.feldmann.AbstractNode;

public class Node extends AbstractNode{
	public static int STEP = 10; //StepCost (The G cost)
	Node Parent = null;
	byte H, BH;
	byte G, BG;
	byte F, BF;
	public byte x,y;
	int dist = 9999;
	Node DParent = null;
	boolean blocked = false;
	boolean readBlocked = false;
	
	public Node(byte x, byte y) {
		this.x = x;
		this.y = y;
	}
	
	public ArrayList<Node> getNeighbors(Node[][] Grid) {
		/** Returns 4-adjacency neighbors of node target  **/
		ArrayList<Node> neighbors = new ArrayList<Node>();
		byte x = this.x;
		byte y = this.y;
		if(x+1 < AStar.M) {neighbors.add(Grid[x+1][y]);}
		if(x-1 >= 0) neighbors.add(Grid[x-1][y]);
		if(y+1 < AStar.M) neighbors.add(Grid[x][y+1]);
		if(y-1 >= 0) neighbors.add(Grid[x][y-1]);
		for(Node n : neighbors) {
			if(!n.readBlocked) {
				n.blocked = AStar.pilot.hasBarrier(this, n);
				n.readBlocked = true;
			}
		}
		return neighbors;
	}

	public boolean isNeib(Node target){
		if(this.x + 1 == target.x && this.y == target.y) return true;
		else if(this.x - 1 == target.x && this.y == target.y) return true;
		else if(this.x == target.x && this.y + 1 == target.y) return true;
		else if(this.x == target.x && this.y - 1 == target.y) return true;
		return false;
	}

	@Override
	public byte getX() {
		return x;
	}

	@Override
	public byte getY() {
		return y;
	}

}
