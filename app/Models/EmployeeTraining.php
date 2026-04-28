<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class EmployeeTraining extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'training_id',
        'status',
        'certificate_path',
        'score',
        'start_date',
        'end_date',
        'validity_months',
        'notes',
    ];

    protected $casts = [
        'start_date'      => 'date',
        'end_date'        => 'date',
        'score'           => 'decimal:2',
        'validity_months' => 'integer',
    ];

    // ── Relacionamentos ────────────────────────────────

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    // ── Accessors computados ───────────────────────────

    /**
     * Data de expiração = end_date + validity_months.
     * Retorna null se end_date ou validity_months não estiverem preenchidos.
     */
    public function getExpiryDateAttribute(): ?Carbon
    {
        if ($this->end_date && $this->validity_months) {
            return $this->end_date->copy()->addMonths($this->validity_months);
        }
        return null;
    }

    /**
     * Estado de validade:
     *  - 'expired'   → já expirou
     *  - 'expiring'  → expira nos próximos 30 dias
     *  - 'valid'     → válida
     *  - null        → sem validade definida
     */
    public function getValidityStatusAttribute(): ?string
    {
        $expiry = $this->expiry_date;
        if (! $expiry) {
            return null;
        }
        $today = Carbon::today();
        if ($expiry->lt($today)) {
            return 'expired';
        }
        if ($expiry->diffInDays($today) <= 30) {
            return 'expiring';
        }
        return 'valid';
    }
}
