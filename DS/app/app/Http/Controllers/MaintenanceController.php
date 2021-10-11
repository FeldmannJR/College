<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        $users = [
            ['id' => 1, 'type' => 'A', 'name' => 'Raquel Fonseca', 'pass' => 'he123'],
            ['id' => 2, 'type' => 'S', 'name' => 'Mônica Vargas', 'pass' => 'he123'],
            ['id' => 3, 'type' => 'R', 'name' => 'Fábio Martins', 'pass' => 'he123'],
        ];

        return view('maintenance.index', ['users' => $users]);
    }
}
