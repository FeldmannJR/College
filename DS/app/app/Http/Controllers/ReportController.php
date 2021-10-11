<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function index()
    {
    $indicators = [
        ['id' => 1, 'name' => 'Indicador1', 'enabled' => 'true'],
        ['id' => 2, 'name' => 'Indicador2', 'enabled' => 'true'],
        ['id' => 3, 'name' => 'Indicador3', 'enabled' => 'true'],
        ['id' => 4, 'name' => 'Indicador4', 'enabled' => 'true'],
        ['id' => 5, 'name' => 'Indicador5', 'enabled' => 'true'],
        ['id' => 6, 'name' => 'Indicador6', 'enabled' => 'true'],
        ['id' => 7, 'name' => 'Indicador7', 'enabled' => 'true'],
        ['id' => 8, 'name' => 'Indicador8', 'enabled' => 'true'],
        ['id' => 9, 'name' => 'Indicador9', 'enabled' => 'true'],
        ['id' => 10, 'name' => 'Indicador10', 'enabled' => 'true'],
        ['id' => 11, 'name' => 'Indicador11', 'enabled' => 'true'],
    ];
    return view('report.index', ['indicators' => $indicators]);
}

public function generatePDF()
{
    $indicators = [
        ['id' => 1, 'name' => 'Indicador1', 'enabled' => 'true'],
        ['id' => 2, 'name' => 'Indicador2', 'enabled' => 'true'],
        ['id' => 3, 'name' => 'Indicador3', 'enabled' => 'true'],
        ['id' => 4, 'name' => 'Indicador4', 'enabled' => 'true'],
        ['id' => 5, 'name' => 'Indicador5', 'enabled' => 'true'],
        ['id' => 6, 'name' => 'Indicador6', 'enabled' => 'true'],
        ['id' => 7, 'name' => 'Indicador7', 'enabled' => 'true'],
        ['id' => 8, 'name' => 'Indicador8', 'enabled' => 'true'],
        ['id' => 9, 'name' => 'Indicador9', 'enabled' => 'true'],
        ['id' => 10, 'name' => 'Indicador10', 'enabled' => 'true'],
        ['id' => 11, 'name' => 'Indicador11', 'enabled' => 'true'],
    ];

    $data = ['title' => $indicators[0]['name']];
    $pdf = \PDF::loadView('report.report', $data);
        return $pdf->download('relatório.pdf');
    }
}
