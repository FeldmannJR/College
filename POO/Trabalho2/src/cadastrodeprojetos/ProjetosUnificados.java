package cadastrodeprojetos;

import java.util.HashMap;

public class ProjetosUnificados implements Projetos {


    HashMap<String, Projeto> projetos = new HashMap<>();

    @Override
    public void guarda(Projeto p) {
        projetos.put(p.pegaCodigo(), p);
    }

    @Override
    public Projeto recupera(String codigo) {
        if (projetos.containsKey(codigo)) {
            return projetos.get(codigo);
        }
        return null;
    }

    @Override
    public void informarNumeroDeHorasSemanais(String codigo, int horas) {
        Projeto p = recupera(codigo);
        if (p != null) {
            p.cargaHorariaSemanal(horas);
        }
    }

    @Override
    public String toString() {
        StringBuilder builder = new StringBuilder();
        for (Projeto p : projetos.values()) {
            builder.append('\n');
            builder.append(" " + p.toString());
        }
        return builder.toString();
    }
}
