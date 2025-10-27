<?php

namespace App\Http\Controllers\Pemerintah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PemerintahDashboardController extends Controller
{
    public function index()
    {
        return view('pemerintah.dashboard');
    }
}
