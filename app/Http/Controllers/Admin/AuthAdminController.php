<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthAdminController extends Controller {

    public function showLogin(): View {
        return view('admin.auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

        // Verificar si el usuario es administrador
        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->isAdmin()) {
            return back()->withErrors([
                'email' => 'No tienes permisos para acceder al panel administrativo.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            RateLimiter::clear($this->throttleKey($request));
            // Registrar actividad
            $this->logActivity('Inició sesión en el panel administrativo');

            return redirect()->intended(route('admin.dashboard'))->with('success', '¡Bienvenido al panel administrativo!');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no son correctas.',
        ])->onlyInput('email');
    }

    protected function throttleKey(Request $request): string {
        return Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
    }

    public function checkTooManyFailedAttempts(Request $request) {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        throw new \Exception('Demasiados intentos. Intente nuevamente en 1 minuto.');
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request) {
        $this->logActivity('Cerró sesión del panel administrativo');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('success', 'Sesión cerrada exitosamente.');
    }
}
