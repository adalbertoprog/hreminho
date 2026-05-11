<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ── Sincronização automática com DocsElectro-Minho ───────────────────────────
// Sincroniza todos os funcionários ativos todos os dias às 02:00
Schedule::command('docsem:sync --status=active')
    ->dailyAt('02:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/docsem-sync.log'));

// Sincronização completa (ativos + inativos) todas as segundas-feiras às 03:00
Schedule::command('docsem:sync --status=all')
    ->weeklyOn(1, '03:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/docsem-sync.log'));
