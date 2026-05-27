<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateEmployeeUsers extends Command
{
    protected $signature   = 'employees:create-users
                                {--dry-run : Mostrar o que seria feito sem criar nada}';
    protected $description = 'Cria utilizadores para todos os funcionarios activos sem conta associada.';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        $employees = Employee::where('status', 'active')
                             ->whereNull('user_id')
                             ->get();

        if ($employees->isEmpty()) {
            $this->info('Nenhum funcionario activo sem conta encontrado.');
            return self::SUCCESS;
        }

        $this->info("Funcionarios a processar: {$employees->count()}");
        if ($dryRun) {
            $this->warn('-- Modo dry-run: nenhuma alteracao sera feita --');
        }

        $created  = 0;
        $skipped  = 0;
        $errors   = 0;

        $bar = $this->output->createProgressBar($employees->count());
        $bar->start();

        foreach ($employees as $emp) {
            try {
                // Username unico baseado no codigo (minusculas)
                $username = strtolower($emp->code);

                // Se ja existe um utilizador com este email ou username, associa e passa a frente
                $existingByEmail = $emp->email
                    ? User::where('email', $emp->email)->first()
                    : null;

                if ($existingByEmail) {
                    if (! $dryRun) {
                        $emp->user_id = $existingByEmail->id;
                        $emp->save();
                    }
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                // Email: usar o real se existir, senao gerar um interno
                $email = $emp->email ?: "{$username}@hrelectrominho.local";

                // Verificar se o email gerado ja existe
                if (User::where('email', $email)->exists()) {
                    $email = "{$username}.{$emp->id}@hrelectrominho.local";
                }

                if (! $dryRun) {
                    $user = User::create([
                        'name'     => $emp->full_name,
                        'email'    => $email,
                        'password' => Hash::make('12345678'),
                        'role'     => 'employee',
                    ]);

                    $emp->user_id = $user->id;
                    $emp->save();
                }

                $created++;
            } catch (\Throwable $e) {
                $this->newLine();
                $this->error("Erro em {$emp->code} ({$emp->full_name}): {$e->getMessage()}");
                $errors++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->table(
            ['Resultado', 'Total'],
            [
                ['Utilizadores criados',   $created],
                ['Ja tinham conta (ligada)', $skipped],
                ['Erros',                  $errors],
            ]
        );

        if (! $dryRun && $created > 0) {
            $this->info("Feito! Password padrao: 12345678");
        }

        return self::SUCCESS;
    }
}
