package robotica;

import lejos.nxt.LCD;
import lejos.nxt.UltrasonicSensor;

public class MasterRobo extends LightRobo {
	
	float parede = 20;

	public UltrasonicSensor distance;

	public MasterRobo() {		
		distance = new UltrasonicSensor(Config.ultraSonic);
	}
	@Override
	public boolean onTick() {
		super.onTick();
		
		
		int u = getUltraDistance();
		double l = getLightDistance();
		//A diferenca entre os sensores, o sensor de som fica mais perto da parede do que o inicial do carro
		double distance;
		if(u==255) {
			distance = l;
		}else {
			u-=20;
			distance = (Config.w*l) + ((1-Config.w)*u);
		}
		
		LCD.drawString("Distance: "+distance, 0,0);
		LCD.drawString("Ultra: "+u, 0,1);
		LCD.drawString("Light: "+l, 0,2);
		if(distance < 100) {
			move();
		}else {
			stop((int)distance);
		}
		return false;
	}
	
	public int getUltraDistance() {
		return distance.getDistance();
	}
}
	