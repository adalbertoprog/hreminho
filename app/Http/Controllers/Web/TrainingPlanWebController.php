<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Training;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;

class TrainingPlanWebController extends Controller
{
    public function index()
    {
        Gate::authorize('manage-hr');

        $trainings = Training::orderBy('title')->get(['id', 'title', 'provider']);
        $currentYear = Carbon::now()->year;

        return view('trainings.plan', compact('trainings', 'currentYear'));
    }
}
