/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package usuarios;

import java.util.List;

/**
 *
 * @author felipe
 */
public class Usuario implements Pessoa {
    private String nome;
    private String cpf;
    private String email;
    private Data dataNascimento;
    
    public Usuario(String nome, String cpf, String email, Data data) {
        this.nome = nome;
        this.cpf = cpf;
        this.email = email;
        this.dataNascimento = data;
    }
    
    public String pegaNome() {
        return nome;
    }
    
    public String pegaCPF() {
        return cpf;
    }
    
    public String pegaEmail() {
        return email;
    }
    
    public Data pegaDataDeNascimento() {
        return dataNascimento;
    }
    
    //*
    @Override
    public String toString() {
        return "Nome: " + nome + "\t|\tCPF: " + cpf 
                + "\ne-mail: " + email
                + "\nData de Nascimento: " + dataNascimento;
    }
    //*/
}
