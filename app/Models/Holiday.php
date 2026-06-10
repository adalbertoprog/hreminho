<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class Holiday extends Model
{
    protected $fillable = ['name', 'date', 'type', 'repeats_yearly'];

    protected $casts = [
        'date'           => 'date',
        'repeats_yearly' => 'boolean',
    ];

    /**
     * Verifica se uma data é feriado.
     * Para feriados com repeats_yearly=true, compara apenas mês e dia.
     */
    public static function isHoliday(string|Carbon $date): bool
    {
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
        $dateStr = $carbon->format('Y-m-d');
        $md      = $carbon->format('m-d');

        return Cache::remember("holiday_{$dateStr}", 3600, function () use ($dateStr, $md) {
            // Feriado fixo nesse ano exacto
            if (static::whereDate('date', $dateStr)->exists()) {
                return true;
            }
            // Feriado recorrente (mesmo mês/dia, qualquer ano)
            return static::where('repeats_yearly', true)
                ->whereRaw("DATE_FORMAT(date, '%m-%d') = ?", [$md])
                ->exists();
        });
    }

    /**
     * Retorna o nome do feriado para uma data (ou null).
     */
    public static function nameFor(string|Carbon $date): ?string
    {
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
        $dateStr = $carbon->format('Y-m-d');
        $md      = $carbon->format('m-d');

        $holiday = static::whereDate('date', $dateStr)->first()
            ?? static::where('repeats_yearly', true)
                     ->whereRaw("DATE_FORMAT(date, '%m-%d') = ?", [$md])
                     ->first();

        return $holiday?->name;
    }

    /**
     * Invalida a cache quando um feriado é criado/actualizado/eliminado.
     */
    protected static function booted(): void
    {
        static::saved(fn()   => Cache::flush());
        static::deleted(fn() => Cache::flush());
    }
}
