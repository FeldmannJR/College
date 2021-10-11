package me.feldmannjr.aula1;

import java.util.Random;

public class Main {

    public static void main(String[] args) {

        try {
            Random rnd = new Random();
            Library lib = new Library(2);
            for (int x = 0; x < 20; x++) {
                lib.addBook(new Book("livro " + x, "teste", 50 + rnd.nextInt(200)));
            }
            lib.print();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}
