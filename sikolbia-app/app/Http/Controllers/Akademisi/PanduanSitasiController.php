<?php

namespace App\Http\Controllers\Akademisi;

use App\Http\Controllers\Controller;

class PanduanSitasiController extends Controller
{
    public function index()
    {
        return view('akademisi.panduan-sitasi');
    }
}
