package usuarios;

import cadastrodeprojetos.Projeto;
import cadastrodeprojetos.Projetos;
import cadastrodeprojetos.ProjetosUnificados;

public class Aluno extends Usuario {

    private String matricula;
    private Data primeiraMatricula;

    private Projetos projetos;

    public Aluno(String nome, String cpf, String email, Data data, String matricula, Data primeiraMatricula) {
        super(nome, cpf, email, data);
        this.matricula = matricula;
        this.primeiraMatricula = primeiraMatricula;
        projetos = new ProjetosUnificados();
    }

    public void vincularProjeto(Projeto p) {
        projetos.guarda(p);
    }

    public void informarCargaHorariaSemanal(Projeto projeto, int horas) {
        projetos.informarNumeroDeHorasSemanais(projeto.pegaCodigo(), horas);
    }

    @Override
    public String toString() {
        return super.toString() +
                "\nMatricula: " + matricula +
                "\nPrimeira Matricula:" + primeiraMatricula.toString() +
                "\nProjetos: " + projetos.toString();
    }
}
