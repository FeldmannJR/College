<?php

namespace App\Http\Controllers;

use App\Enums\Symbols;

use App\Indicators\Indicator;
use App\Presentation\Slide;
use Illuminate\Http\Request;

class PanelController extends Controller
{
    public function index()
    {
        $fixed = [
            [
                "name" => "Indicador Fixo1",
                "text" => "Indicador Fixo1",
                "value" => "50%"
            ],
            [
                "name" => "Indicador Fixo2",
                "text" => "Indicador Fixo2",
                "value" => "72.9"
            ],
            [
                "name" => "Indicador Fixo3",
                "text" => "Indicador Fixo3",
                "value" => "30"
            ],
            [
                "name" => "Indicador Fixo4 de Indicador de Fixo",
                "text" => "Indicador Fixo4 de Indicador de Fixo",
                "value" => "N/A"
            ],
        ];

        $presentation = [
            [
                'timer' => 2,
                'order' => 1,
                'slide' => [
                    [
                        [
                            "id" => '0',
                            "type" => "numeric",
                            "name" => "Texto Indicador1",
                            "text" => "Texto Indicador1",
                            "value" => "50%"
                        ],
                        [
                            "id" => '1',
                            "type" => "statistic",
                            "name" => "Texto Indicador2",
                            "text" => "Texto Indicador2",
                            "graph" => "bar",
                            "data" => [30, 50],
                            "units" => ["A", "B"]
                        ],
                    ],
                    [
                        [
                            "id" => '2',
                            "type" => "numeric",
                            "name" => "Texto Indicador6",
                            "text" => "Texto Indicador6",
                            "value" => "25"
                        ],
                        [

                            "id" => '4',
                            "type" => "statistic",
                            "name" => "Texto Indicador4",
                            "text" => "Texto Indicador4",
                            "graph" => "pie",
                            "data" => [30, 25, 10],
                            "units" => ["Red", "Yellow", "Blue"]
                        ]
                    ],
                ]
            ],
            [
                'timer' => 1,
                'order' => 3,
                'slide' => [
                    [
                        [
                            "id" => '3',
                            "type" => "multiple",
                            "name" => "Texto Indicador3",
                            "text" => "Texto Indicador3",
                            "graph" => "none",
                            "data" => [1, 2, 3, 4, 5, 6],
                            "units" => ["Label1", "Label2", "Label3", "Label4", "Label5", "Label6"]
                        ],
                    ],
                ]
            ],
            [
                'timer' => 2,
                'order' => 2,
                'slide' => [
                    [

                        [
                            "id" => '4',
                            "type" => "statistic",
                            "name" => "Texto Indicador4",
                            "text" => "Texto Indicador4",
                            "graph" => "pie",
                            "data" => [30, 25, 10],
                            "units" => ["Red", "Yellow", "Blue"]
                        ]
                    ]
                ]
            ]
        ];
        $presentation = Slide::getPresentation();
        $indicators = Indicator::all()->toArray();
        return view('panel.index', ['presentation' => $presentation, 'fixed' => $fixed, 'indicators' => $indicators]);
    }
}
