package robotica.ufpel.feldmann;

public abstract class AbstractNode {

	public abstract byte getX();
	public abstract byte getY();

	public Direction getDirection(AbstractNode to) {
		int difX = to.getX()- getX();
		int difY = to.getY()- getY();
		if(difX > 0 && difY==0) {
			return Direction.DIREITA;
		}else if(difX < 0 && difY==0) {
			return Direction.ESQUERDA;
		}else if(difX==0 && difY>0) {
			return Direction.CIMA;
		}else if(difX == 0 && difY<0) {
			return Direction.BAIXO;
		}	
		return null;
	}
}
