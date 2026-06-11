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
 * Encapsula todas as comunicacoes com a API do DocsElectro-Minho.
 * Configuracao em config/services.php (chave 'docselectrominho').
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

    // ── Verificar se a integracao esta ativa e configurada ────────────────────

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

    // ── Sincronizacao em lote ─────────────────────────────────────────────────

    /**
     * Sincroniza uma colecao de funcionarios com o DocsElectro-Minho.
     * Divide automaticamente em lotes de 100.
     *
     * @param Collection<Employee> $employees
     * @return array ['criados' => int, 'atualizados' => int, 'erros' => array]
     */
    public function sincronizarFuncionarios(Collection $employees): array
    {
        if (! $this->estaConfigurado()) {
            return ['erro' => 'Integracao nao configurada. Defina DOCSEM_API_TOKEN no .env'];
        }

        $totalCriados     = 0;
        $totalAtualizados = 0;
        $totalErros       = [];

        // Dividir em lotes de 100
        foreach ($employees->chunk(100) as $lote) {
            $payload = $lote->map(fn(Employee $e) => $this->mapearParaApi($e))->values()->toArray();

            try {
                $response = $this->post('/funcionarios/sync', ['funcionarios' => $payload]);

                if ($response->successful()) {
                    $data = $response->json();
                    $totalCriados     += $data['criados']     ?? 0;
                    $totalAtualizados += $data['atualizados'] ?? 0;
                    $totalErros        = array_merge($totalErros, $data['erros'] ?? []);
                } else {
                    $totalErros[] = [
                        'mensagem' => 'HTTP ' . $response->status() . ': ' . $response->body(),
                    ];
                    Log::error('[DocsEM] Erro na sincronizacao', [
                        'status' => $response->status(),
                        'body'   => $response->body(),
                    ]);
                }
            } catch (\Exception $e) {
                $totalErros[] = ['mensagem' => $e->getMessage()];
                Log::error('[DocsEM] Excecao na sincronizacao: ' . $e->getMessage());
            }
        }

        return [
            'sucesso'     => empty($totalErros),
            'criados'     => $totalCriados,
            'atualizados' => $totalAtualizados,
            'total'       => $totalCriados + $totalAtualizados,
            'erros'       => $totalErros,
        ];
    }

    // ── Sincronizar um unico funcionario ──────────────────────────────────────

    public function sincronizarFuncionario(Employee $employee): array
    {
        if (! $this->estaConfigurado()) {
            return ['erro' => 'Integracao nao configurada.'];
        }

        try {
            // Tentar atualizar primeiro (via rh_employee_id)
            $response = $this->put("/funcionarios/rh:{$employee->id}", $this->mapearParaApi($employee));

            if ($response->status() === 404) {
                // Nao existe -- criar
                $response = $this->post('/funcionarios', $this->mapearParaApi($employee));
            }

            if ($response->successful()) {
                return ['sucesso' => true, 'dados' => $response->json('dados')];
            }

            return ['erro' => 'HTTP ' . $response->status(), 'detalhe' => $response->json()];

        } catch (\Exception $e) {
            Log::error('[DocsEM] Erro ao sincronizar funcionario ' . $employee->id . ': ' . $e->getMessage());
            return ['erro' => $e->getMessage()];
        }
    }

    // ── Consultar documentos de um funcionario ────────────────────────────────

    public function documentosDoFuncionario(int $rhEmployeeId): array
    {
        if (! $this->estaConfigurado()) {
            return ['erro' => 'Integracao nao configurada.'];
        }

        try {
            $response = $this->get("/funcionarios/rh:{$rhEmployeeId}/documentos");

            if ($response->successful()) {
                return $response->json();
            }

            if ($response->status() === 404) {
                return ['erro' => 'Funcionario nao encontrado no DocsElectro-Minho.'];
            }

            return ['erro' => 'HTTP ' . $response->status()];

        } catch (\Exception $e) {
            return ['erro' => $e->getMessage()];
        }
    }

    // ── Empresas subcontratadas ───────────────────────────────────────────────

    /**
     * Lista empresas do DocsElectro-Minho.
     *
     * @param array $filtros  search, tipo, estado, per_page
     * @return array  ['data' => [...], 'meta' => [...]] ou ['erro' => '...']
     */
    public function getEmpresas(array $filtros = []): array
    {
        if (! $this->estaConfigurado()) {
            return ['erro' => 'Integracao nao configurada.'];
        }

        try {
            $params   = array_merge(['estado' => 'ativa', 'per_page' => 500], $filtros);
            $response = $this->client()->get($this->baseUrl . '/empresas', $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('[DocsEM] getEmpresas HTTP ' . $response->status());
            return ['erro' => 'HTTP ' . $response->status(), 'data' => []];

        } catch (\Exception $e) {
            Log::error('[DocsEM] getEmpresas: ' . $e->getMessage());
            return ['erro' => $e->getMessage(), 'data' => []];
        }
    }

    /**
     * Retorna uma empresa pelo ID do DocsElectro-Minho.
     *
     * @return array empresa ou ['erro' => '...']
     */
    public function getEmpresa(int $docsemEmpresaId): array
    {
        if (! $this->estaConfigurado()) {
            return ['erro' => 'Integracao nao configurada.'];
        }

        try {
            $response = $this->get("/empresas/{$docsemEmpresaId}");

            if ($response->successful()) {
                return $response->json();
            }

            if ($response->status() === 404) {
                return ['erro' => 'Empresa nao encontrada.'];
            }

            return ['erro' => 'HTTP ' . $response->status()];

        } catch (\Exception $e) {
            Log::error('[DocsEM] getEmpresa: ' . $e->getMessage());
            return ['erro' => $e->getMessage()];
        }
    }

    // ── Remover funcionario do DocsElectro-Minho ──────────────────────────────

    public function removerFuncionario(int $rhEmployeeId): bool
    {
        if (! $this->estaConfigurado()) return false;

        try {
            $response = $this->delete("/funcionarios/rh:{$rhEmployeeId}");
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('[DocsEM] Erro ao remover funcionario ' . $rhEmployeeId . ': ' . $e->getMessage());
            return false;
        }
    }

    // ── Mapeamento Employee (RH) para payload da API DocsEM ───────────────────

    private function mapearParaApi(Employee $employee): array
    {
        return [
            'id'            => $employee->id,
            'code'          => $employee->code,
            'first_name'    => $employee->first_name,
            'last_name'     => $employee->last_name,
            'email'         => $employee->email,
            'phone'         => $employee->phone,
            'date_of_birth' => $employee->date_of_birth?->format('Y-m-d'),
            'hire_date'     => $employee->hire_date?->format('Y-m-d'),
            'end_date'      => $employee->end_date?->format('Y-m-d'),
            'status'        => $employee->status,
            'contract_type' => $employee->contract_type,
            'position'      => $employee->position?->position,
            'department'    => $employee->department?->department,
            'sector'        => $employee->sector?->sector,
            'work_location' => $employee->work_location ?? null,
            'profile_photo' => $employee->profile_photo ?? null,
        ];
    }

    // ── Metodos HTTP internos ─────────────────────────────────────────────────

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
