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

    public function login(LoginValidate $request) {
        $validated = $request->validated();

        $user = User::where('email', $request['email'])->first();
        if (!$user) {
            RateLimiter::hit($this->throttleKey($request));
            return back()->withErrors([
                'email' => 'Revise el usuario y/o contraseña.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();

            RateLimiter::clear($this->throttleKey($request));

            if ($user->isAdmin() || $user->isInstructor()) {
                return redirect()->intended(route('admin.dashboard'))->with('success', '¡Bienvenido al panel administrativo!');
            } else if ($user->isStudent()) {
                return redirect()->intended('dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no son correctas.',
        ])->onlyInput('email');

    }

    protected function ensureIsNotRateLimited(Request $request): void {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
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
