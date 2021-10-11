package cadastrodeprojetos;

public class ProjetoAluno extends Projeto {

    private int numeroDeHorasSemanais;

    public ProjetoAluno(String codigo, String titulo, String enfase) {
        super(codigo, titulo, enfase);
    }

    @Override
    public void cargaHorariaSemanal(int numeroDeHoras) {
        this.numeroDeHorasSemanais = numeroDeHoras;
    }

    public int getNumeroDeHorasSemanais() {
        return numeroDeHorasSemanais;
    }

    @Override
    public String toString() {
        return super.toString() + "\t Horas Semanais: " + numeroDeHorasSemanais;
    }
}
