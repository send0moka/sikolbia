<?php

namespace App\Http\Controllers\Akademisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AkademisiDashboardController extends Controller
{
    public function index()
    {
        return view('akademisi.dashboard');
    }
}
