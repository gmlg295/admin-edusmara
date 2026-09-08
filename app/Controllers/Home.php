<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('content/home', [
            'title' => 'Dashboard Admin - EDUSMARA',
            'active' => 'dashboard',
            'load_js' => 'dashboard.js',
        ]);
    }
}
