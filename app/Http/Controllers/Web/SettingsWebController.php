<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class SettingsWebController extends Controller
{
    public function index()
    {
        Gate::authorize('manage-hr');
        return view('settings.index');
    }
}
