<?php

namespace App\Indicators\Calculators;

use App\Enums\UpdateType;
use App\Indicators\Calculators\Base\IndicatorSQLCalculator;
use App\Indicators\Indicator;
use App\Unit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TaxaOcupacaoGeralCalculator extends IndicatorSQLCalculator
{

    public function convert($value)
    {
        return (number_format($value, 2) * 100) . '%';
    }

    /**
     * Calcula o indicador de uma unidade especifica se a unidade for null
     * ele calcula o geral
     * @param Indicator $indicator
     * @param Carbon|null $data
     * @return double valor do indicador calculado
     */
    public function calculateIndicator(Indicator $indicator, Carbon $data = null)
    {
        $query = "SELECT cast(A.paciente_dia as float) / greatest(B.total_leitos,1) AS RESULT
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
                                          '01-0121-B')) B;";
        $rs = $this->getHeConnection()->selectOne($this->replaceDates($query, $data));

        return reset($rs);

    }
}
