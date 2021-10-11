/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package lista;

import usuarios.Pessoa;
import java.util.ArrayList;
import java.util.List;

/**
 *
 * @author felipe
 */
public class MeuRepositorio implements Repositorio {
    private List<Pessoa> lista;
    private int indice;
    
    public MeuRepositorio() {
        lista = new ArrayList<Pessoa>();
        indice = 0;
    }

    @Override
    public void guarda(Pessoa nova) {
        lista.add(nova);
    }

    @Override
    public Pessoa recupera(String cpf) {
        for (Pessoa p : lista) {
            if (p.pegaCPF().equals(cpf))
                return p;
        }
        return null;
    }

    @Override
    public Pessoa primeiro() {
        indice = 0;
        return lista.get(indice);
    }

    @Override
    public Pessoa proximo() {
        if (indice < lista.size())
            indice++;
        if (indice < lista.size())
            return lista.get(indice);
        else
            return null;
    }
}
