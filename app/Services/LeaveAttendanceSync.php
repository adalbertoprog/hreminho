<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Leave;
use Carbon\Carbon;

class LeaveAttendanceSync
{
    /**
     * Sincronizar uma licença com os registos de presença.
     * - Se aprovada: limpar registos anteriores e recriar para o período actual.
     * - Se não aprovada (pending/rejected): apagar registos gerados por esta licença.
     */
    public function sync(Leave $leave): void
    {
        if ($leave->status === 'approved') {
            // Limpar sempre os registos antigos desta licença antes de recriar
            // (garante que mudanças de datas são reflectidas correctamente)
            $this->removeAttendances($leave);
            $this->createAttendances($leave);
        } else {
            $this->removeAttendances($leave);
        }
    }

    /**
     * Apagar todos os registos de presença gerados por esta licença.
     */
    public function removeAttendances(Leave $leave): void
    {
        // Apagar por leave_id (registos com origem conhecida)
        Attendance::where('leave_id', $leave->id)->delete();

        // Apagar também on_leave sem leave_id no período, para licenças criadas antes da migração
        $start = Carbon::parse($leave->start_date);
        $end   = Carbon::parse($leave->end_date);
        Attendance::where('employee_id', $leave->employee_id)
                  ->whereNull('leave_id')
                  ->where('status', 'on_leave')
                  ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                  ->delete();
    }

    /**
     * Criar registos on_leave para cada dia útil (Seg–Sex) do período.
     * Chamado apenas após removeAttendances, por isso não há conflitos.
     */
    private function createAttendances(Leave $leave): void
    {
        $start  = Carbon::parse($leave->start_date);
        $end    = Carbon::parse($leave->end_date);
        $cursor = $start->copy();

        while ($cursor->lte($end)) {
            if (!$cursor->isWeekend()) {
                $date = $cursor->toDateString();

                // Verificar se existe registo manual (não gerado por licença)
                $existing = Attendance::where('employee_id', $leave->employee_id)
                                      ->whereDate('date', $date)
                                      ->first();

                if ($existing) {
                    // Sobrescrever com on_leave e associar a esta licença
                    $existing->update([
                        'status'   => 'on_leave',
                        'leave_id' => $leave->id,
                    ]);
                } else {
                    Attendance::create([
                        'employee_id' => $leave->employee_id,
                        'date'        => $date,
                        'status'      => 'on_leave',
                        'leave_id'    => $leave->id,
                    ]);
                }
            }

            $cursor->addDay();
        }
    }
}
