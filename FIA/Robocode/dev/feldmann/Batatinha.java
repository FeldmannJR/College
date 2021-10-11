package dev.feldmann;

import robocode.*;
import robocode.Robot;
import robocode.util.Utils;

import java.awt.*;
import java.util.Random;

public class Batatinha extends TeamRobot {


    boolean lock = false;


    @Override
    public void run() {
        setupColor();
        while (true) {
            loop();
        }
    }


    public void loop() {
        if (!lock) {
            for (int x = 0; x < 18; x++)
                setTurnGunLeft(12);
        }
        setAhead(300);
        setMaxVelocity(Rules.MAX_VELOCITY);
        setMaxTurnRate(Rules.MAX_TURN_RATE);
        execute();

    }


    public void setupColor() {
        Color brown = Color.decode("0xe0ae60");
        Color brown2 = Color.decode("0xdea347");
        Color ketchup = Color.decode("0xc90606");
        setAllColors(brown);
        setBodyColor(brown2);
        setScanColor(brown);
        setGunColor(ketchup);
        setRadarColor(brown2);
    }

    @Override
    public void onScannedRobot(ScannedRobotEvent event) {

        if (isTeammate(event.getName())) {
            return;
        }
        lock = true;
        int shootpower = getShootPower(event);
        if (shootpower > 0)
            fire(shootpower);
        scan();
        lock = false;

    }

    public int getShootPower(ScannedRobotEvent event) {
        double distance = event.getDistance();
        if (getGunHeat() != 0) return 0;
        if (distance > 800) {
            return 0;
        }
        if (getEnergy() <= 5) {
            return 0;
        }
        if (getEnergy() < 10) {
            return 1;
        }
        if (distance < 66.6) {
            return (int) getEnergy() / 2;
        }
        if (distance < 100) {
            return 2;
        }

        return 1;
    }

    @Override
    public void onHitWall(HitWallEvent event) {
        stop();

        turnLeft(95);

    }

    @Override
    public void onHitRobot(HitRobotEvent event) {
        stop();
        turnRight(90);
    }
}
