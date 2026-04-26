<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'leave_id',
        'file_name',
        'file_path',
        'file_type',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function leave()
    {
        return $this->belongsTo(Leave::class);
    }
}
