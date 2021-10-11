/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package cadastrodeprojetos;

/**
 *
 * @author felipe
 */
public abstract class Projeto {
    private final String codigo;
    private final String titulo;
    private final String enfase;
    
    public Projeto(String codigo, String titulo, String enfase) {
        this.codigo = codigo;
        this.titulo = titulo;
        this.enfase = enfase;
    }
    
    public String pegaCodigo() {
        return codigo;
    }

    public String pegaTitulo() {
        return titulo;
    }

    public abstract void cargaHorariaSemanal(int numeroDeHoras);
    
    @Override
    public String toString() {
        return "Código: " + codigo + "\t| Título: " + titulo
               + "\t  Ênfase: " + enfase;
    }
}
