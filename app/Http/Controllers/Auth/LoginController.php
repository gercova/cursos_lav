<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginValidate;
use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    public function __construct() {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function showLogin(): View {
        $enterprise = Enterprise::first();
        return view('auth.login', compact('enterprise'));
    }

    // public function login(LoginValidate $request) {
    //     $validated  = $request->validated();
    //     $user       = User::where('email', $request['email'])->first();
    //     if (!$user) {
    //         RateLimiter::hit($this->throttleKey($request));
    //         return back()->withErrors([
    //             'email' => 'Revise el usuario y/o contraseña.',
    //         ])->onlyInput('email');
    //     }

    //     if($user->expires_at != NULL || !$user->isAdmin()){
    //         if($user->expires_at->format('Y-m-d') == now()->format('Y-m-d')){
    //             return back()->withErrors(['Tu cuenta a caducado, contacte con nuestro canal de atención al cliente.']);
    //         }
    //     }

    //     if (Auth::attempt($validated)) {
    //         $request->session()->regenerate();

    //         RateLimiter::clear($this->throttleKey($request));

    //         if ($user->isAdmin() || $user->isInstructor()) {
    //             return redirect()->intended(route('admin.dashboard'))->with('success', '¡Bienvenido al panel administrativo!');
    //         } else if ($user->isStudent()) {
    //             return redirect()->intended('dashboard');
    //         } else if ($user->isBusiness()) {
    //             return redirect()->intended(route('company.list'))->with('success', '!Bienvenido de nuevo¡');
    //         }
    //     }

    //     return back()->withErrors([
    //         'email' => 'Las credenciales proporcionadas no son correctas.',
    //     ])->onlyInput('email');

    // }

    public function login(LoginValidate $request) {
        $this->ensureIsNotRateLimited($request);

        $validated = $request->validated();
        $user      = User::where('email', $validated['email'])->first();

        if (!$user) {
            RateLimiter::hit($this->throttleKey($request));
            return back()->withErrors([
                'email' => 'Revise el usuario y/o contraseña.',
            ])->onlyInput('email');
        }

        if (!$user->is_active) {
            RateLimiter::hit($this->throttleKey($request));
            return back()->withErrors([
                'email' => 'Tu cuenta está desactivada. Contacta con soporte.',
            ])->onlyInput('email');
        }

        if ($user->expires_at != NULL && !$user->isAdmin()) {
            if ($user->expires_at->format('Y-m-d') <= now()->format('Y-m-d')) {
                return back()->withErrors([
                    'email' => 'Tu cuenta ha caducado, contacte con nuestro canal de atención al cliente.',
                ])->onlyInput('email');
            }
        }

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();
            RateLimiter::clear($this->throttleKey($request));

            if ($user->isAdmin() || $user->isInstructor()) {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', '¡Bienvenido al panel administrativo!');
            } elseif ($user->isStudent()) {
                return redirect()->intended('dashboard');
            } elseif ($user->isBusiness()) {
                return redirect()->intended(route('company.list'))
                    ->with('success', '¡Bienvenido de nuevo!');
            } else {
                return redirect()->intended('dashboard');
            }
        }

        RateLimiter::hit($this->throttleKey($request));
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no son correctas.',
        ])->onlyInput('email');
    }

    protected function ensureIsNotRateLimited(Request $request): void {
        $key = $this->throttleKey($request); // cachear la clave, no llamarla 3 veces

        if (!RateLimiter::tooManyAttempts($key, 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($key);
        $minutes = ceil($seconds / 60);

        // Evento estándar de Laravel para lockouts (útil para logs y listeners)
        event(new \Illuminate\Auth\Events\Lockout($request));

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => $minutes,
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function throttleKey(Request $request): string {
        return Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->intended(route('login'));
    }
}
