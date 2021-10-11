package robotica.ufpel.feldmann;

public enum Direction {
	
	CIMA(0),
	//DIREITA
	DIREITA(90),
	BAIXO(180),
	//ESQUERDA
	ESQUERDA(-90);
	

	int rot;
	private Direction(int rot) {
		this.rot =rot;	
	}
	public int getRot() {
		return rot;
	}
	public Direction right() {
		if(ordinal()+1>=values().length) {
			return values()[0];
		}
		return values()[ordinal()+1];
	}
	public Direction left() {
		if(ordinal()==0) {
			return values()[values().length-1];
		}
		return values()[ordinal()-1];
	}
}
