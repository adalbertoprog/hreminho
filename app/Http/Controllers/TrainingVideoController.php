<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\TrainingVideo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TrainingVideoController extends Controller
{
    public function index(Training $training): JsonResponse
    {
        return response()->json(['data' => $training->videos]);
    }

    public function store(Request $request, Training $training): JsonResponse
    {
        $this->authorizeManager();

        $isUpload = $request->hasFile('video_file');

        if ($isUpload) {
            $request->validate([
                'title'      => 'required|string|max:255',
                'video_file' => 'required|file|mimetypes:video/mp4,video/webm,video/ogg,video/quicktime|max:512000',
                'description'=> 'nullable|string',
                'order'      => 'sometimes|integer|min:1',
            ]);

            $file     = $request->file('video_file');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/training-videos', $filename);
            $url = '/storage/training-videos/' . $filename;

            $video = $training->videos()->create([
                'title'       => $request->input('title'),
                'url'         => $url,
                'description' => $request->input('description'),
                'order'       => $request->input('order', $training->videos()->max('order') + 1),
                'is_uploaded' => true,
            ]);
        } else {
            $request->validate([
                'title'       => 'required|string|max:255',
                'url'         => 'required|string|max:2048',
                'description' => 'nullable|string',
                'order'       => 'sometimes|integer|min:1',
            ]);

            $video = $training->videos()->create([
                'title'       => $request->input('title'),
                'url'         => $request->input('url'),
                'description' => $request->input('description'),
                'order'       => $request->input('order', $training->videos()->max('order') + 1),
                'is_uploaded' => false,
            ]);
        }

        return response()->json(['data' => $video], 201);
    }

    public function show(TrainingVideo $video): JsonResponse
    {
        return response()->json(['data' => $video]);
    }

    public function update(Request $request, TrainingVideo $video): JsonResponse
    {
        $this->authorizeManager();

        $data = $request->validate([
            'title'       => 'sometimes|string|max:255',
            'url'         => 'sometimes|string|max:2048',
            'description' => 'nullable|string',
            'order'       => 'sometimes|integer|min:1',
        ]);

        $video->update($data);

        return response()->json(['data' => $video->fresh()]);
    }

    public function destroy(TrainingVideo $video): JsonResponse
    {
        $this->authorizeManager();
        $video->delete(); // model booted() handles file deletion
        return response()->json(['message' => 'Video eliminado.']);
    }

    private function authorizeManager(): void
    {
        if (!in_array(Auth::user()->role, ['admin', 'hr'])) {
            abort(403, 'Acesso nao autorizado.');
        }
    }
}
