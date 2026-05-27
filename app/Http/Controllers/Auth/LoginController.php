<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required'],
        ], [
            'login.required'    => 'O e-mail ou codigo de funcionario e obrigatorio.',
            'password.required' => 'A palavra-passe e obrigatoria.',
        ]);

        $loginValue = trim($request->input('login'));
        $remember   = $request->boolean('remember');

        // Determinar se e email ou codigo de funcionario
        if (str_contains($loginValue, '@')) {
            // Login por e-mail (admin, hr, employees com email)
            $user = User::where('email', $loginValue)->first();
        } else {
            // Login por codigo de funcionario (ex: FUN0488)
            $code     = strtoupper($loginValue);
            $employee = Employee::whereRaw('UPPER(code) = ?', [$code])
                                 ->whereNotNull('user_id')
                                 ->first();

            if (! $employee) {
                return back()
                    ->withInput($request->only('login'))
                    ->withErrors(['login' => 'Codigo de funcionario nao encontrado ou sem conta associada.']);
            }

            $user = User::find($employee->user_id);
        }

        // Verificar credenciais manualmente para suportar ambos os fluxos
        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            return back()
                ->withInput($request->only('login'))
                ->withErrors(['login' => 'Credenciais incorretas. Verifique o e-mail/codigo e a palavra-passe.']);
        }

        Auth::login($user, $remember);
        $request->session()->regenerate();

        $role    = $user->role;
        $default = $role === 'employee'
            ? route('employee.dashboard')
            : route('dashboard');

        return redirect()->intended($default);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
