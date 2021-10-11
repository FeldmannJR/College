package robotica;

import lejos.nxt.ColorSensor;
import lejos.nxt.LCD;
import lejos.nxt.LightSensor;
import lejos.robotics.Color;

public class LightRobo extends Robo{

	ColorSensor light;
	float andou = 0;
	
	int andouSteps = 0;
	int andouPreto = 0;
	int andouBranco =0;
	
	boolean lastCor = false;
	
	public LightRobo() {
		 light = new ColorSensor(Config.lightSensor);
		 lastCor = isBlack();
		 light.calibrateLow();
	}

	
	@Override
	public boolean onTick() {
		boolean cor = isBlack();
		if(cor!=lastCor) {
			andouSteps++;
			if(lastCor) {
				andou+=1.07f;
			}else{
				andou+=0.57;
			}
			
		}
		lastCor = cor;
		return false;
	}
	public float getLightDistance() {
		return andou;
	}
	public boolean isBlack() {
		return light.getColorID()==7;
	}
	
	@Override
	public void stop(int d) {
		andou = 0;
		andouSteps = 0;
		andouBranco = 0;
		andouPreto = 0;
		super.stop(d);
	}
}
