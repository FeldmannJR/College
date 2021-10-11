package me.feldmannjr.aula1;

public class Book {

    private String title;
    private String author;
    private int pages;
    private String refCode = "";
    private int borrowed = 0;

    public Book(String title, String author, int pages) {
        this.title = title;
        this.author = author;
        this.pages = pages;
    }

    public String getAuthor() {
        return author;
    }

    public String getTitle() {
        return title;
    }

    public int getPages() {
        return pages;
    }

    public String getRefCode() {
        return refCode;
    }

    public int getBorrowed() {
        return borrowed;
    }

    public void setRefCode(String refCode) {
        if (refCode == null || refCode.length() < 2) {
            System.out.println("Erro! O refcode precisa ter no minimo 3 chars e não pode ser nulo!");
            return;
        }
        this.refCode = refCode;
    }

    public void borrow() {
        borrowed++;
    }

    public void printTitle() {
        System.out.println("Titulo: " + title);
    }

    public void printAutor() {
        System.out.println("Autor: " + author);
    }

    public void printDetails() {
        System.out.println("Title: " + title
                + "  Author: " + author
                + "  Pages:" + pages
                + "  RefCode: " + (refCode.length() == 0 ? "ZZZ" : refCode)
                + "  Borrowed: "+borrowed
        );
    }

}
