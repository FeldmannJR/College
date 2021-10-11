/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package cobalto;

import cadastrodeprojetos.ProjetosUnificados;
import cadastrodeprojetos.Projeto;
import cadastrodeprojetos.Projetos;
import io.DataBase;

import java.io.IOException;
import java.util.Scanner;

import lista.*;
import usuarios.*;

/**
 * @author felipe
 */
public class Cobalto {
    private Repositorio alunos;
    private Projetos projetos;
    private String cpf;

    public Cobalto() {
        alunos = new MeuRepositorio();
        projetos = new ProjetosUnificados();
        cpf = null;

        try {
            DataBase.lerAlunosDoArquivo("DadosDosAlunos.txt", alunos);
        } catch (IOException e) {
            System.err.println("Erro na abertura do arquivo de alunos.\n" + e.getMessage());
            System.exit(1);
        }

        try {
            DataBase.lerProjetosDoArquivo("Projetos.txt", projetos);
        } catch (IOException e) {
            System.err.println("Erro na abertura do arquivo de projetos.\n" + e.getMessage());
            System.exit(1);
        }
    }

    public void login() {
        boolean login = false;
        while (login == false) {
            System.out.println("+=======================================================+");
            System.out.println("|                        COBALTO                        |");
            System.out.println("+=======================================================+");
            System.out.print("Digite seu CPF: ");
            Scanner sc = new Scanner(System.in);
            cpf = sc.nextLine();
            Login l = new MeuLogin();
            login = l.verifica(cpf, alunos);
        }
    }

    public void menu() {
        char opcao;
        do {
            System.out.println("+=======================================================+");
            System.out.println("| 1 - Mostrar dados do aluno                            |");
            System.out.println("| 2 - Vincular a um projeto                             |");
            System.out.println("| 3 - Sair                                              |");
            System.out.println("+=======================================================+");
            System.out.print("Digite o número correspondente a uma das opções acima: ");
            Scanner sc = new Scanner(System.in);
            opcao = sc.next().charAt(0);
            switch (opcao) {
                case '1':
                    System.out.println("\n-- Dados do Aluno -------------------------------------------");
                    System.out.println(alunos.recupera(cpf));
                    break;
                case '2':
                    matriculaDisciplina();
                    break;
            }
        } while (opcao != '3');
    }

    public void matriculaDisciplina() {
        System.out.println("\n-- Lista Completa de Projetos Cadastrados ------------------------");
        System.out.println(projetos);
        System.out.print("Digite o código do projeto: ");
        Scanner sc = new Scanner(System.in);
        String codigo = sc.nextLine();
        Projeto p = projetos.recupera(codigo);
        if (p != null) {
            ((Aluno) alunos.recupera(cpf)).vincularProjeto(p);
            System.out.print("Digite o número de horas semanais: ");
            int horas = sc.nextInt();
            ((Aluno) alunos.recupera(cpf)).informarCargaHorariaSemanal(p, horas);
        } else
            System.out.println("\nCódigo da disciplina inválido!");
    }

    public void imprimirListagemDeAlunos() {
        Pessoa aluno = alunos.primeiro();
        if (aluno != null) {
            do {
                System.out.println("=======");
                System.out.println(aluno.toString());
                aluno = alunos.proximo();
            } while (aluno != null);
        }
        // Implemente aqui o método da questão 4
    }

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        Cobalto cobalto = new Cobalto();

        cobalto.login();
        cobalto.menu();

        cobalto.imprimirListagemDeAlunos();
    }


}
