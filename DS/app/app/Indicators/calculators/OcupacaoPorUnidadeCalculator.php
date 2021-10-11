<?php


namespace App\Indicators\Calculators;


use App\Enums\UpdateType;
use App\Indicators\Calculators\Base\IndicatorSQLCalculator;
use App\Indicators\Indicator;
use App\Unit;
use Carbon\Carbon;

class OcupacaoPorUnidadeCalculator extends IndicatorSQLCalculator
{
    public function convert($value)
    {
        return (number_format($value,2) * 100);
    }

    /**
     * Calcula o indicador de uma unidade especifica se a unidade for null
     * ele calcula o geral
     * @param Indicator $indicator
     * @param Carbon|null $data
     * @return double|double[] valor do indicador calculado ou map por unidade
     */
    public function calculateIndicator(Indicator $indicator, Carbon $data = null)
    {
        $rs_pacientes = $this->getHeConnection()->select(
            $this->replaceDates(
                "select unidade.seq as id_unidade,  count(*) as pacientes_dia
                     from agh.v_ain_internacao int inner join agh.agh_unidades_funcionais unidade on unidade.seq = int.unidade_funcional
                    inner join agh.ain_leitos leito on leito.qrt_numero = int.qrt_numero and leito.leito = int.leito
                    where int.ind_paciente_internado='S' and unidade.seq in (4,3,9,7,11,8,15,19,20,14)
                    group by unidade.seq, unidade.descricao;",
                $data));

        $rs_leitos = $this->getHeConnection()->select(
            $this->replaceDates(
                "select unidade.seq as unidade, count(*) as total_leitos from agh.agh_unidades_funcionais unidade 
                    inner join agh.ain_leitos leito on leito.unf_seq = unidade.seq 
                    and unidade.seq in (4,3,9,7,11,8,15,19,20,14) and leito.ind_situacao='A' 
                    and lto_id not in ('01-0121-A', '01-0121-B')
                    group by unidade.seq, unidade.descricao;",
                $data));


        $pacientes = [];
        foreach ($rs_pacientes as $p) {
            $pacientes[$p->id_unidade] = $p->pacientes_dia;
        }
        $returnArray = [];
        foreach ($rs_leitos as $l) {
            $qtd_pacientes = 0;
            if (array_key_exists($l->unidade, $pacientes)) {
                $qtd_pacientes = $pacientes[$l->unidade];
            }
            $leitos = $l->total_leitos;
            $returnArray[$l->unidade] = $qtd_pacientes / max(1, $leitos);

        }
        return $returnArray;
    }
}