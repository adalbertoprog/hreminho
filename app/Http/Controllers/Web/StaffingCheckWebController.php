<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Training;
use Illuminate\Support\Facades\Gate;

class StaffingCheckWebController extends Controller
{
    public function index()
    {
        Gate::authorize('manage-hr');

        $trainings = Training::orderBy('title')->get(['id', 'title']);

        return view('trainings.staffing-check', compact('trainings'));
    }
}
