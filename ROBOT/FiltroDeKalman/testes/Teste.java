package teste;
import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;

public class Teste {

	
	
	public static void main(String[] args) throws IOException {
		
		
		Scanner s1 = new Scanner(new File("testeluz.txt"));
		double media1= 0;
		List<Double> numeros1 = new ArrayList<>();
		while(s1.hasNextLine()) {
			double f = Double.valueOf(s1.nextLine());
		//	System.out.println(f);
			media1 += f;
			numeros1.add(f);
		}
		s1.close();
		media1/=numeros1.size();
		
		
		Scanner s2 = new Scanner(new File("testeultrasom.txt"));
		double media2= 0;
		List<Double> numeros2 = new ArrayList<>();
		while(s2.hasNextLine()) {
			double f = Double.valueOf(s2.nextLine());
			//System.out.println(f);
			media2 += f;
			numeros2.add(f);
		}
		s2.close();
		media2/=numeros2.size();
		
		double variancia1 = 0;
		double variancia2 = 0;
		
		for(double f : numeros1) {
			variancia1 += Math.pow(media1-f,2f);
		}
		
		for(double f : numeros2) {
			variancia2 += Math.pow(media2-f,2f);
		}
		
		variancia1 /= numeros1.size();
		variancia2 /= numeros2.size();

		// 1 = Luz
		// 2= Ultrasom
		
		//Ganho de kalman
		System.out.println("V1 " +variancia1+" \t V2 "+variancia2);
		double w = variancia2/(variancia1+variancia2);
		System.out.println("W: "+w);
		
	}
}
