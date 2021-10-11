package robotica.ufpel.feldmann.teste;

import robotica.ufpel.feldmann.AbstractNode;

public class FNodo extends AbstractNode{
	byte x;
	byte y;
	NodoStatus status = NodoStatus.UNKOWN;
	boolean visitado = false;
	
	public FNodo(byte x,byte y) {
		this.x = x;
		this.y = y;
	}
	
	public static enum NodoStatus{
		UNKOWN,
		BARRIER,
		EMPTY
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
