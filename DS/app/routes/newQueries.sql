------------Taxa de ocupação geral ---pacientes-dia/numero de leitos-----------------------------------------------------------------------------------'

-- 'Pacientes-dia'
select count(*) as paciente_dia from agh.v_ain_internacao int inner join agh.agh_unidades_funcionais unidade on unidade.seq = int.unidade_funcional
inner join agh.ain_leitos leito on leito.qrt_numero = int.qrt_numero and leito.leito = int.leito
where int.ind_paciente_internado='S' and unidade.seq in (4,3,9,7,11,8,15,19,20,14);

-- 'Numero de leitos sem contar o pré-parto'
select count(*) as total_leitos from agh.agh_unidades_funcionais unidade
inner join agh.ain_leitos leito on leito.unf_seq = unidade.seq
and unidade.seq in (4,3,9,7,11,8,15,19,20,14) and leito.ind_situacao='A'
and lto_id not in ('01-0121-A', '01-0121-B');

-- Vai virar

SELECT cast(A.paciente_dia as float) / greatest(B.total_leitos,1) AS RESULT
FROM
    (SELECT count(*) AS paciente_dia
     FROM agh.v_ain_internacao int
              INNER JOIN agh.agh_unidades_funcionais unidade ON unidade.seq = int.unidade_funcional
              INNER JOIN agh.ain_leitos leito ON leito.qrt_numero = int.qrt_numero
         AND leito.leito = int.leito
     WHERE int.ind_paciente_internado='S'
       AND unidade.seq IN (4,
                           3,
                           9,
                           7,
                           11,
                           8,
                           15,
                           19,
                           20,
                           14)) A,

    (SELECT count(*) AS total_leitos
     FROM agh.agh_unidades_funcionais unidade
              INNER JOIN agh.ain_leitos leito ON leito.unf_seq = unidade.seq
         AND unidade.seq IN (4,
                             3,
                             9,
                             7,
                             11,
                             8,
                             15,
                             19,
                             20,
                             14)
         AND leito.ind_situacao='A'
         AND lto_id NOT IN ('01-0121-A',
                            '01-0121-B')) B;


-- Foi preciso modificar a numero de leitos bloqueados por que foi passado errado
select lto_id as leito, andar_ala_descricao as unidade from agh.v_ain_leitos_limpeza;
-- Virou
select count(*) from agh.v_ain_leitos_limpeza;

------------Número de novas Internações nas últimas 24 horas --Alterar o parãmetro dthr_internacao para obter das últimas 24 horas ----------------'
select count(*) as qtd_nova_internacao
from agh.v_ain_internacao int inner join agh.ain_internacoes int_t on int_t.seq = int.nro_internacao
                              inner join agh.agh_unidades_funcionais unidade  on unidade.seq = int.unidade_funcional
where dthr_internacao >= '07-04-2019 09:30:00'  and unidade_funcional in (4,3,9,7,11,8,15,19,20,14) ;

-- virou isso aqui, botando o interval
select count(*) as qtd_nova_internacao
from agh.v_ain_internacao int inner join agh.ain_internacoes int_t on int_t.seq = int.nro_internacao
                              inner join agh.agh_unidades_funcionais unidade  on unidade.seq = int.unidade_funcional
where dthr_internacao >= NOW() - INTERVAL '24 HOURS'   and unidade_funcional in (4,3,9,7,11,8,15,19,20,14) ;

------------Número de altas das últimas 24 horas---Alterar o parametro dt_saida_paciente------------------------------'
select count(*) as qtd_alta_medica
from agh.v_ain_internacao int inner join agh.ain_internacoes int_t on int_t.seq = int.nro_internacao
                              inner join agh.agh_unidades_funcionais unidade  on unidade.seq = int.unidade_funcional
where dt_saida_paciente >= '07-04-2019 09:30:00' and unidade_funcional in (4,3,9,7,11,8,15,19,20,14) ;
-- virou isso aqui botando intervalos

select count(*) as qtd_alta_medica
from agh.v_ain_internacao int inner join agh.ain_internacoes int_t on int_t.seq = int.nro_internacao
                              inner join agh.agh_unidades_funcionais unidade  on unidade.seq = int.unidade_funcional
where dt_saida_paciente >= NOW() - INTERVAL '24 HOURS' and unidade_funcional in (4,3,9,7,11,8,15,19,20,14) ;

--Média de Permanência Geral do mês anterior - Numero de pacientes-dia/numero de saídas do período (exemplo considera o mês de março - neste caso alterar as datas ao gerar'
--pacientes-dia--'
select sum(
                   (case when dt_saida_paciente>='01-04-2019' then '31-03-2019'
                         when dt_saida_paciente is null then '31-03-2019'
                         else dt_saida_paciente
                       end  ) -
                   (case when data<'01-03-2019' then '01-03-2019'
                         else data
                       end ))
from agh.v_ain_internacao int inner join agh.ain_internacoes int_t on int_t.seq = int.nro_internacao
                              inner join agh.agh_unidades_funcionais unidade  on unidade.seq = int.unidade_funcional
where (dt_saida_paciente is null or dt_saida_paciente>='01-03-2019') and data<'01-04-2019' and unidade_funcional in (4,3,9,7,11,8,15,19,20,14);

--número da saídas no período--'
select count(*)
from agh.v_ain_internacao int inner join agh.ain_internacoes int_t on int_t.seq = int.nro_internacao
                              inner join agh.agh_unidades_funcionais unidade  on unidade.seq = int.unidade_funcional
where dt_saida_paciente>='01-03-2019' and dt_saida_paciente<'01-04-2019' and unidade_funcional in (4,3,9,7,11,8,15,19,20,14) ;


-- vai virar
select sum(
                   (case when dt_saida_paciente>='01-04-2019' then '31-03-2019'
                         when dt_saida_paciente is null then '31-03-2019'
                         else dt_saida_paciente
                       end  ) -
                   (case when data<'01-03-2019' then '01-03-2019'
                         else data
                       end ))
from agh.v_ain_internacao int inner join agh.ain_internacoes int_t on int_t.seq = int.nro_internacao
                              inner join agh.agh_unidades_funcionais unidade  on unidade.seq = int.unidade_funcional
where (dt_saida_paciente is null or dt_saida_paciente>='01-03-2019') and data<'01-04-2019' and unidade_funcional in (4,3,9,7,11,8,15,19,20,14);