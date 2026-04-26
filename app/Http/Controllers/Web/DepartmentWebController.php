<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DepartmentWebController extends Controller
{
    public function index(): View
    {
        return view('departments.index');
    }
}
