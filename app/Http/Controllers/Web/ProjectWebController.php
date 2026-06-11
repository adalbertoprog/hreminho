<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProjectWebController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('projects.index', compact('user'));
    }
}
