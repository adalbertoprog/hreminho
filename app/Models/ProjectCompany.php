<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectCompany extends Model
{
    protected $table = 'project_companies';

    protected $fillable = [
        'project_id',
        'docsem_empresa_id',
        'empresa_nome',
        'empresa_nif',
        'data_entrada',
        'data_saida',
        'observacoes',
    ];

    protected $casts = [
        'data_entrada' => 'date',
        'data_saida'   => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
