<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class PasswordWebController extends Controller
{
    /**
     * Mostra o formulário de mudança de password obrigatória (primeiro login).
     */
    public function changeForm()
    {
        // Se não precisar de mudar, redirecionar para dashboard
        if (!Auth::user()->must_change_password) {
            return redirect()->intended(
                in_array(Auth::user()->role, ['employee', 'manager'])
                    ? route('employee.dashboard')
                    : route('dashboard')
            );
        }

        return view('auth.change-password');
    }

    /**
     * Atualiza a palavra-passe do utilizador autenticado.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'A palavra-passe atual é obrigatória.',
            'password.required'         => 'A nova palavra-passe é obrigatória.',
            'password.confirmed'        => 'A confirmação da palavra-passe não coincide.',
            'password.min'              => 'A palavra-passe deve ter pelo menos 8 caracteres.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('pwd_modal_open', true);
        }

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'A palavra-passe atual está incorreta.'])
                ->with('pwd_modal_open', true);
        }

        $user->update([
            'password'             => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        // Se era uma mudança obrigatória, redirecionar para dashboard
        if ($request->boolean('forced')) {
            $redirect = in_array($user->role, ['employee', 'manager'])
                ? route('employee.dashboard')
                : route('dashboard');
            return redirect($redirect)->with('success_password', 'Palavra-passe alterada com sucesso!');
        }

        return back()->with('success_password', 'Palavra-passe alterada com sucesso!');
    }
}
