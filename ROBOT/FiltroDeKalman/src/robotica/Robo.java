package robotica;

import java.util.ArrayList;
import java.util.List;
import java.util.Vector;

import lejos.nxt.Button;
import lejos.nxt.LCD;
import lejos.nxt.Motor;
import lejos.nxt.NXTRegulatedMotor;
import lejos.nxt.SensorPort;
import lejos.nxt.SensorPortListener;
import lejos.nxt.Sound;
import lejos.nxt.UltrasonicSensor;

public abstract class Robo {

	public int vaiAte = 110;
	
	//Motores
	public NXTRegulatedMotor motorDireita= Motor.A;
	public NXTRegulatedMotor motorEsquerda= Motor.B;
	
	public Robo() {
		
	}
	
	boolean started = false;

	public boolean loop() {
		if(!started && Button.RIGHT.isDown()) {
			start();
		}else if(!started) {
			return false;
		}
		return onTick();
		
	}
	
	public abstract boolean onTick();
	
	public void move() {
		motorDireita.rotate(-Config.speed, true);
		motorEsquerda.rotate(-Config.speed, true);
	}

	public void stop(int d) {
		LCD.clear();
		System.out.println("Parou a "+d+" Aperte RIGHT para comecar novamente!");
		sound();
		started = false;
	}
	
	public void sound() {
		//Sound.playNote(Sound.FLUTE,  293, 2000);
	}
	public void start() {
		LCD.clear();
		started = true;
	}
	public void printStart() {
		 System.out.println("Aperte o botao RIGHT para comecar a andar!");
	}
	
}

