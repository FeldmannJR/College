package robotica;

import lejos.nxt.Button;
import lejos.nxt.ColorSensor;
import lejos.nxt.ColorSensor.Color;
import lejos.nxt.LCD;
import lejos.nxt.LightSensor;
import lejos.nxt.Motor;
import lejos.nxt.SensorPort;
import lejos.nxt.Sound;
import lejos.nxt.UltrasonicSensor;

public class Main {

	public static void main(String [] args) throws Exception {
		 Robo r = new  MasterRobo();
		 r.printStart();
		 while(!Button.ESCAPE.isDown()) {
			 if(r.loop()) {
				 break;
			 }
		 }
		 
	}

}
