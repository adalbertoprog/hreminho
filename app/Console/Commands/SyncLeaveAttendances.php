<?php

namespace App\Console\Commands;

use App\Models\Leave;
use App\Services\LeaveAttendanceSync;
use Illuminate\Console\Command;

class SyncLeaveAttendances extends Command
{
    protected $signature   = 'leaves:sync-attendances';
    protected $description = 'Sincronizar licenças aprovadas existentes com registos de presença';

    public function handle(): int
    {
        $approved = Leave::where('status', 'approved')->get();
        $this->info("A sincronizar {$approved->count()} licença(s) aprovada(s)...");

        $sync = new LeaveAttendanceSync;
        foreach ($approved as $leave) {
            $sync->sync($leave);
            $this->line("  ✓ Leave #{$leave->id} — {$leave->employee?->full_name} ({$leave->start_date?->toDateString()} → {$leave->end_date?->toDateString()})");
        }

        $this->info('Concluído.');
        return Command::SUCCESS;
    }
}
