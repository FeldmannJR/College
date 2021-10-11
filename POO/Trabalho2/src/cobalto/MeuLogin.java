/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package cobalto;

import lista.Repositorio;

/**
 *
 * @author felipe
 */
public class MeuLogin implements Login {

    @Override
    public boolean verifica(String cpf, Repositorio r) {
        return r.recupera(cpf) != null;
    }
}
