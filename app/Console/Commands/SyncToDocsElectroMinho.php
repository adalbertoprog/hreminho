<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Services\DocsElectroMinhoService;
use Illuminate\Console\Command;

class SyncToDocsElectroMinho extends Command
{
    protected $signature = 'docsem:sync
                            {--status=active : Estado dos funcionários a sincronizar (active, inactive, all)}
                            {--id= : Sincronizar apenas um funcionário pelo seu ID}
                            {--ping : Apenas verificar a ligação à API}';

    protected $description = 'Sincroniza funcionários do RH com o DocsElectro-Minho';

    public function __construct(private DocsElectroMinhoService $service)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        // ── Apenas testar ligação ─────────────────────────────────────────────
        if ($this->option('ping')) {
            $this->info('A verificar ligação ao DocsElectro-Minho...');
            if ($this->service->ping()) {
                $this->info('✅ Ligação bem sucedida!');
            } else {
                $this->error('❌ Não foi possível ligar ao DocsElectro-Minho.');
                $this->line('Verifique DOCSEM_API_URL e se o servidor está acessível.');
                return self::FAILURE;
            }
            return self::SUCCESS;
        }

        // ── Verificar configuração ────────────────────────────────────────────
        if (! $this->service->estaConfigurado()) {
            $this->error('❌ Integração não configurada.');
            $this->line('Defina DOCSEM_API_TOKEN e DOCSEM_API_URL no ficheiro .env');
            return self::FAILURE;
        }

        // ── Sincronizar funcionário individual ────────────────────────────────
        if ($id = $this->option('id')) {
            $employee = Employee::with(['position', 'department', 'sector'])->find($id);

            if (! $employee) {
                $this->error("Funcionário com ID {$id} não encontrado.");
                return self::FAILURE;
            }

            $this->info("A sincronizar: {$employee->full_name} ({$employee->code})...");
            $resultado = $this->service->sincronizarFuncionario($employee);

            if (isset($resultado['sucesso'])) {
                $this->info('✅ Funcionário sincronizado com sucesso.');
            } else {
                $this->error('❌ Erro: ' . ($resultado['erro'] ?? 'Erro desconhecido'));
                return self::FAILURE;
            }

            return self::SUCCESS;
        }

        // ── Sincronização em lote ─────────────────────────────────────────────
        $status = $this->option('status');

        $query = Employee::with(['position', 'department', 'sector']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $total = $query->count();

        if ($total === 0) {
            $this->warn("Nenhum funcionário encontrado com status='{$status}'.");
            return self::SUCCESS;
        }

        $this->info("A sincronizar {$total} funcionário(s) com o DocsElectro-Minho...");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $employees = $query->get();
        $bar->advance($total);
        $bar->finish();
        $this->newLine();

        $resultado = $this->service->sincronizarFuncionarios($employees);

        // ── Resultado ─────────────────────────────────────────────────────────
        $this->newLine();
        $this->table(
            ['Criados', 'Atualizados', 'Total', 'Erros'],
            [[
                $resultado['criados']    ?? 0,
                $resultado['atualizados'] ?? 0,
                $resultado['total']      ?? 0,
                count($resultado['erros'] ?? []),
            ]]
        );

        if (! empty($resultado['erros'])) {
            $this->warn('Erros encontrados:');
            foreach ($resultado['erros'] as $erro) {
                $this->line('  • ' . ($erro['mensagem'] ?? json_encode($erro)));
            }
            return self::FAILURE;
        }

        $this->info('✅ Sincronização concluída com sucesso!');
        return self::SUCCESS;
    }
}
