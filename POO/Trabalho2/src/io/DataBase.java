/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package io;


import cadastrodeprojetos.*;
import usuarios.*;
import lista.Repositorio;
import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;

/**
 *
 * @author felipe
 */
public class DataBase {
    public static void lerAlunosDoArquivo(String nomeArquivo, Repositorio lista) throws IOException {
        BufferedReader buffRead = new BufferedReader(new FileReader(nomeArquivo));
        String linha = null;
        
        do {
            linha = buffRead.readLine();
            if (linha != null) {
                Pessoa pessoa = DataBase.parseAluno(linha);
                if (pessoa != null)
                    lista.guarda(pessoa);
            }
        } while (linha != null);
        
        buffRead.close();
    }
    
    private static Pessoa parseAluno(String linha) {
        Pessoa p = null;
        //*
        if (linha != null) {
            String[] palavras = linha.split(";");
            if (palavras.length == 6) {
                p = new Aluno(
                    palavras[0],
                    palavras[1],
                    palavras[2],
                    DataBase.parseData(palavras[3]),
                    palavras[4],
                    DataBase.parseData(palavras[5])
                    );
            } else {
                System.err.println("Erro: número de dados inválidos em uma linha do arquivo de entrada.");
            }
        }
        //*/
        return p;
    }

    public static void lerProjetosDoArquivo(String nomeArquivo, Projetos lista) throws IOException {
        BufferedReader buffRead = new BufferedReader(new FileReader(nomeArquivo));
        String linha = null;
        
        do {
            linha = buffRead.readLine();
            if (linha != null) {
                Projeto projeto = DataBase.parseProjeto(linha);
                if (projeto != null)
                    lista.guarda(projeto);
            }
        } while (linha != null);
        
        buffRead.close();
    }
    
    private static Projeto parseProjeto(String linha) {
        Projeto d = null;
        //*
        if (linha != null) {
            String[] palavras = linha.split(";");
            if (palavras.length == 3) {
                d = new ProjetoAluno(
                    palavras[0],
                    palavras[1],
                    palavras[2]
                    );
            } else {
                System.err.println("Erro: número de dados inválidos em uma linha do arquivo de entrada.");
            }
        }
        //*/
        return d;
    }

    private static Data parseData(String strData) {
        Data data = null;
        String numeros[] = strData.split("/");
        if (numeros.length == 3) {
            data = new Data(Integer.parseInt(numeros[0]),
                            Integer.parseInt(numeros[1]),
                            Integer.parseInt(numeros[2]));
        } else {
            System.err.println("Erro: formato da data inválido '" + strData + "'.");
        }
        return data;
    }

}






