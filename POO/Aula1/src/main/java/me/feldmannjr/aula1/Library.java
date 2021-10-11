package me.feldmannjr.aula1;

import java.util.ArrayList;
import java.util.List;

public class Library {

    Book[] books;

    public Library(int size) throws Exception {
        if (size <= 0) {
            throw new Exception("Initial size of vector cannot be <= 0");
        }
        books = new Book[size];
    }

    public void addBook(Book book) {
        for (int x = 0; x < books.length; x++) {
            if (books[x] == null) {
                books[x] = book;
                return;
            }
        }
        Book[] newbooks = new Book[books.length * 2];
        for (int x = 0; x < books.length; x++) {
            newbooks[x] = books[x];
        }

        newbooks[books.length] = book;
        books = newbooks;
    }

    public Book removeBook(int index) {
        if (books.length >= index) {
            return null;
        }
        Book b = books[index];
        books[index] = null;
        return b;
    }

    public int findTitle(String title) {
        for (int x = 0; x < books.length; x++) {
            Book b = books[x];
            if (b != null) {
                if (title.equals(b.getTitle())) {
                    return x;
                }
            }
        }
        return -1;
    }

    public Book[] findTitles(String title) {
        List<Book> found = new ArrayList<Book>();
        for (Book b : books) {
            if (b != null) {
                if (b.getTitle().contains(title)) {
                    found.add(b);
                }
            }
        }
        Book[] toReturn = new Book[found.size()];
        found.toArray(toReturn);
        return toReturn;
    }

    public void print() {
        for (Book b : books) {
            if (b != null) {
                b.printDetails();
            }
        }
    }

}
