<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class ReportWebController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }
}
