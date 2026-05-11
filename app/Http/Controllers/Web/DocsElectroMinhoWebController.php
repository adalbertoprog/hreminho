<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Services\DocsElectroMinhoService;
use Illuminate\Http\Request;

class DocsElectroMinhoWebController extends Controller
{
    public function __construct(private DocsElectroMinhoService $service) {}

    /**
     * Página de estado da integração.
     */
    public function index()
    {
        $configurado = $this->service->estaConfigurado();
        $online      = $configurado ? $this->service->ping() : false;
        $totalAtivos = Employee::where('status', 'active')->count();
        $totalAll    = Employee::count();

        return view('docsem.index', compact('configurado', 'online', 'totalAtivos', 'totalAll'));
    }

    /**
     * Disparar sincronização completa de todos os ativos.
     */
    public function syncTodos(Request $request)
    {
        if (! $this->service->estaConfigurado()) {
            return back()->with('docsem_erro', 'Integração não configurada. Defina DOCSEM_API_TOKEN no .env');
        }

        $status    = $request->get('status', 'active');
        $employees = Employee::with(['position', 'department', 'sector'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->get();

        $resultado = $this->service->sincronizarFuncionarios($employees);

        if (! empty($resultado['erro'])) {
            return back()->with('docsem_erro', $resultado['erro']);
        }

        $msg = "✅ Sincronização concluída: {$resultado['criados']} criados, {$resultado['atualizados']} atualizados.";
        if (! empty($resultado['erros'])) {
            $msg .= ' (' . count($resultado['erros']) . ' erros — ver logs)';
        }

        return back()->with('docsem_sucesso', $msg);
    }

    /**
     * Sincronizar um único funcionário.
     */
    public function syncFuncionario(Employee $employee)
    {
        if (! $this->service->estaConfigurado()) {
            return back()->with('docsem_erro', 'Integração não configurada.');
        }

        $employee->load(['position', 'department', 'sector']);
        $resultado = $this->service->sincronizarFuncionario($employee);

        if (isset($resultado['sucesso'])) {
            return back()->with('docsem_sucesso', "✅ {$employee->full_name} sincronizado com o DocsElectro-Minho.");
        }

        return back()->with('docsem_erro', 'Erro: ' . ($resultado['erro'] ?? 'Desconhecido'));
    }

    /**
     * Ver documentos de um funcionário no DocsElectro-Minho.
     */
    public function documentosFuncionario(Employee $employee)
    {
        $resultado = $this->service->documentosDoFuncionario($employee->id);
        return response()->json($resultado);
    }

    /**
     * Ping à API — endpoint AJAX.
     */
    public function ping()
    {
        $ok = $this->service->ping();
        return response()->json([
            'online'    => $ok,
            'mensagem'  => $ok ? 'DocsElectro-Minho está acessível.' : 'Não foi possível ligar ao DocsElectro-Minho.',
        ]);
    }
}
