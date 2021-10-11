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
public interface Projetos {
    public void guarda(Projeto p);
    public Projeto recupera(String codigo);
    public void informarNumeroDeHorasSemanais(String codigo, int horas);
}
