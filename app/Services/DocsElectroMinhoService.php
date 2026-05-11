<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * DocsElectroMinhoService
 *
 * Encapsula todas as comunicações com a API do DocsElectro-Minho.
 * Configuração em config/services.php (chave 'docselectrominho').
 */
class DocsElectroMinhoService
{
    private string $baseUrl;
    private string $token;
    private bool   $enabled;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.docselectrominho.url', ''), '/');
        $this->token   = config('services.docselectrominho.token', '');
        $this->enabled = (bool) config('services.docselectrominho.enabled', true);
    }

    // ── Verificar se a integração está ativa e configurada ────────────────────

    public function estaConfigurado(): bool
    {
        return $this->enabled && ! empty($this->token) && ! empty($this->baseUrl);
    }

    // ── Health Check ─────────────────────────────────────────────────────────

    public function ping(): bool
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/ping");
            return $response->successful();
        } catch (\Exception $e) {
            Log::warning('[DocsEM] Ping falhou: ' . $e->getMessage());
            return false;
        }
    }

    // ── Sincronização em lote ─────────────────────────────────────────────────

    /**
     * Sincroniza uma coleção de funcionários com o DocsElectro-Minho.
     * Divide automaticamente em lotes de 100.
     *
     * @param Collection<Employee> $employees
     * @return array ['criados' => int, 'atualizados' => int, 'erros' => array]
     */
    public function sincronizarFuncionarios(Collection $employees): array
    {
        if (! $this->estaConfigurado()) {
            return ['erro' => 'Integração não configurada. Defina DOCSEM_API_TOKEN no .env'];
        }

        $totalCriados    = 0;
        $totalAtualizados = 0;
        $totalErros      = [];

        // Dividir em lotes de 100
        foreach ($employees->chunk(100) as $lote) {
            $payload = $lote->map(fn(Employee $e) => $this->mapearParaApi($e))->values()->toArray();

            try {
                $response = $this->post('/funcionarios/sync', ['funcionarios' => $payload]);

                if ($response->successful()) {
                    $data = $response->json();
                    $totalCriados    += $data['criados']    ?? 0;
                    $totalAtualizados += $data['atualizados'] ?? 0;
                    $totalErros       = array_merge($totalErros, $data['erros'] ?? []);
                } else {
                    $totalErros[] = [
                        'mensagem' => 'HTTP ' . $response->status() . ': ' . $response->body(),
                    ];
                    Log::error('[DocsEM] Erro na sincronização', [
                        'status' => $response->status(),
                        'body'   => $response->body(),
                    ]);
                }
            } catch (\Exception $e) {
                $totalErros[] = ['mensagem' => $e->getMessage()];
                Log::error('[DocsEM] Exceção na sincronização: ' . $e->getMessage());
            }
        }

        return [
            'sucesso'    => empty($totalErros),
            'criados'    => $totalCriados,
            'atualizados'=> $totalAtualizados,
            'total'      => $totalCriados + $totalAtualizados,
            'erros'      => $totalErros,
        ];
    }

    // ── Sincronizar um único funcionário ──────────────────────────────────────

    public function sincronizarFuncionario(Employee $employee): array
    {
        if (! $this->estaConfigurado()) {
            return ['erro' => 'Integração não configurada.'];
        }

        try {
            // Tentar atualizar primeiro (via rh_employee_id)
            $response = $this->put("/funcionarios/rh:{$employee->id}", $this->mapearParaApi($employee));

            if ($response->status() === 404) {
                // Não existe — criar
                $response = $this->post('/funcionarios', $this->mapearParaApi($employee));
            }

            if ($response->successful()) {
                return ['sucesso' => true, 'dados' => $response->json('dados')];
            }

            return ['erro' => 'HTTP ' . $response->status(), 'detalhe' => $response->json()];

        } catch (\Exception $e) {
            Log::error('[DocsEM] Erro ao sincronizar funcionário ' . $employee->id . ': ' . $e->getMessage());
            return ['erro' => $e->getMessage()];
        }
    }

    // ── Consultar documentos de um funcionário ────────────────────────────────

    public function documentosDoFuncionario(int $rhEmployeeId): array
    {
        if (! $this->estaConfigurado()) {
            return ['erro' => 'Integração não configurada.'];
        }

        try {
            $response = $this->get("/funcionarios/rh:{$rhEmployeeId}/documentos");

            if ($response->successful()) {
                return $response->json();
            }

            if ($response->status() === 404) {
                return ['erro' => 'Funcionário não encontrado no DocsElectro-Minho.'];
            }

            return ['erro' => 'HTTP ' . $response->status()];

        } catch (\Exception $e) {
            return ['erro' => $e->getMessage()];
        }
    }

    // ── Remover funcionário do DocsElectro-Minho ──────────────────────────────

    public function removerFuncionario(int $rhEmployeeId): bool
    {
        if (! $this->estaConfigurado()) return false;

        try {
            $response = $this->delete("/funcionarios/rh:{$rhEmployeeId}");
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('[DocsEM] Erro ao remover funcionário ' . $rhEmployeeId . ': ' . $e->getMessage());
            return false;
        }
    }

    // ── Mapeamento Employee (RH) → payload da API DocsEM ─────────────────────

    private function mapearParaApi(Employee $employee): array
    {
        return [
            'id'          => $employee->id,
            'code'        => $employee->code,
            'first_name'  => $employee->first_name,
            'last_name'   => $employee->last_name,
            'email'       => $employee->email,
            'phone'       => $employee->phone,
            'date_of_birth' => $employee->date_of_birth?->format('Y-m-d'),
            'hire_date'   => $employee->hire_date?->format('Y-m-d'),
            'end_date'    => $employee->end_date?->format('Y-m-d'),
            'status'      => $employee->status,
            'contract_type' => $employee->contract_type,
            'position'    => $employee->position?->name,
            'department'  => $employee->department?->name,
            'sector'      => $employee->sector?->name,
            'work_location' => $employee->work_location ?? null,
            'profile_photo' => $employee->profile_photo ?? null,
        ];
    }

    // ── Métodos HTTP internos ─────────────────────────────────────────────────

    private function client()
    {
        return Http::withToken($this->token)
            ->acceptJson()
            ->timeout(15);
    }

    private function get(string $endpoint): Response
    {
        return $this->client()->get($this->baseUrl . $endpoint);
    }

    private function post(string $endpoint, array $data = []): Response
    {
        return $this->client()->post($this->baseUrl . $endpoint, $data);
    }

    private function put(string $endpoint, array $data = []): Response
    {
        return $this->client()->put($this->baseUrl . $endpoint, $data);
    }

    private function delete(string $endpoint): Response
    {
        return $this->client()->delete($this->baseUrl . $endpoint);
    }
}
