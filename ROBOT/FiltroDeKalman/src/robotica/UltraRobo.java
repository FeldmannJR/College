package robotica;

import java.util.ArrayList;
import java.util.List;

import lejos.nxt.Button;
import lejos.nxt.LCD;
import lejos.nxt.UltrasonicSensor;

public class UltraRobo extends Robo{

	int andouCom255 = 0;
	int lastDistance = -1;
	
	//Sensores
	public UltrasonicSensor distance;
	
	public UltraRobo() {
		distance = new UltrasonicSensor(Config.ultraSonic);
	}
	
	@Override
	public boolean onTick() {
	
		int d =	getDistance();
		if(d == 255) {
			LCD.drawString("ERRO", 0, 0);
			move();
			return false;
		}
		lastDistance = d;
		if(d<vaiAte) {
			move();
			LCD.drawInt(d, 0, 0);
		}else {
			stop(d);
		}
		return false;
	}

	public int getDistance() {
		return distance.getDistance();
	}
	
	
	
}
