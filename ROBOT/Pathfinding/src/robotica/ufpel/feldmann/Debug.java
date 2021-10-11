package robotica.ufpel.feldmann;

import java.util.ArrayList;
import java.util.List;

import lejos.nxt.Button;
import lejos.nxt.ButtonListener;
import lejos.nxt.LCD;
import lejos.nxt.Motor;
import lejos.nxt.NXTRegulatedMotor;

public class Debug {
	
	
	public static int rotation = 260;
	private static NXTRegulatedMotor motorDireita = Motor.C;
	private static NXTRegulatedMotor motorEsquerda = Motor.A;
	static int size = 275;
	
	
	public static void main(String[] args) {
		alwaysExit();
		Button.waitForAnyPress();
		rotation = pilot.getDegreesToRotation();
		size = pilot.getPerDistance();
		debugDistancia();
		
		
	}
	
	static Controller pilot = new Controller();
	
	public static void fixedList() {
		List<Action> ac = new ArrayList();
		ac.add(Action.FORWARD);
		ac.add(Action.FORWARD);
		ac.add(Action.FORWARD);
		ac.add(Action.ROTATE_LEFT);
		ac.add(Action.FORWARD);
		ac.add(Action.FORWARD);
		ac.add(Action.FORWARD);
		ac.add(Action.ROTATE_LEFT);
		ac.add(Action.FORWARD);
		ac.add(Action.FORWARD);
		ac.add(Action.FORWARD);
		ac.add(Action.ROTATE_LEFT);
		ac.add(Action.FORWARD);
		ac.add(Action.FORWARD);
		ac.add(Action.FORWARD);
		ac.add(Action.ROTATE_LEFT);
		for(Action c :ac) {
			pilot.doAction(c);
			try {
				Thread.sleep(500);
			} catch (InterruptedException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
		
	}	
	
	public static void debugDistancia() {
	
		Button.ENTER.addButtonListener(new ButtonListener() {
			
			@Override
			public void buttonReleased(Button b) {
				pilot.updatePerDistance(size);
				pilot.forward();
				LCD.clear();
			}
			
			@Override
			public void buttonPressed(Button b) {
				// TODO Auto-generated method stub
				
			}
		});
		Button.LEFT.addButtonListener(new ButtonListener() {
			
			@Override
			public void buttonReleased(Button b) {
				size+=1;
				LCD.drawString("SIZE:"+size, 0, 7);
			}
			
			@Override
			public void buttonPressed(Button b) {
				
			}
		});
		Button.RIGHT.addButtonListener(new ButtonListener() {
			
			@Override
			public void buttonReleased(Button b) {
				size-=1;
				LCD.drawString("SIZE:"+size, 0, 7);
				
			}
			
			@Override
			public void buttonPressed(Button b) {
				// TODO Auto-generated method stub
				
			}
		});
		Button.ESCAPE.waitForPressAndRelease();
	}
	
	public static void debugRotation() {
		
		Button.ENTER.addButtonListener(new ButtonListener() {
			
			@Override
			public void buttonReleased(Button b) {
	
				pilot.setDegreesToRotation(rotation);
				for(int x =0;x<4;x++) {
						pilot.right();
					
					
				}
				LCD.clear();
			}
			
			@Override
			public void buttonPressed(Button b) {
				// TODO Auto-generated method stub
				
			}
		});
		Button.LEFT.addButtonListener(new ButtonListener() {
			
			@Override
			public void buttonReleased(Button b) {
				rotation-=1;
				rotation = Math.max(rotation, 0);
				LCD.drawString("ROT:"+rotation, 0, 7);
			}
			
			@Override
			public void buttonPressed(Button b) {
				
			}
		});
		Button.RIGHT.addButtonListener(new ButtonListener() {
			
			@Override
			public void buttonReleased(Button b) {
				rotation+=1;
				rotation = Math.max(rotation, 0);
				LCD.drawString("ROT:"+rotation, 0, 7);
				
			}
			
			@Override
			public void buttonPressed(Button b) {
				// TODO Auto-generated method stub
				
			}
		});
		Button.ESCAPE.waitForPressAndRelease();
	}
	public static void alwaysExit() {
		Button.ESCAPE.addButtonListener(new ButtonListener() {
			
			@Override
			public void buttonReleased(Button b) {
				System.exit(0);				
			}
			
			@Override
			public void buttonPressed(Button b) {
				// TODO Auto-generated method stub
				
			}
		});
	}
}
