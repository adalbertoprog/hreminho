<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TrainingVideo extends Model
{
    use HasFactory;

    protected $fillable = ['training_id', 'title', 'url', 'description', 'order', 'is_uploaded'];

    protected $casts = [
        'is_uploaded' => 'boolean',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (TrainingVideo $video) {
            if ($video->is_uploaded && $video->url) {
                // url is like /storage/training-videos/file.mp4
                // storage path is public/training-videos/file.mp4
                $storagePath = 'public/' . ltrim(str_replace('/storage/', '', $video->url), '/');
                if (Storage::exists($storagePath)) {
                    Storage::delete($storagePath);
                }
            }
        });
    }
}
