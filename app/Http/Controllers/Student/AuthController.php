<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginValidate;
use App\Http\Requests\UserStudentValidate;
use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {

    public function showRegister(): View {
        $enterprise = Enterprise::first();
        return view('student.auth.register', compact('enterprise'));
    }

    public function register(UserStudentValidate $request) {
        $validated = $request->validated();

        $user = User::create([
            'dni'           => $validated['dni'],
            'names'         => $validated['names'],
            'email'         => $validated['email'],
            'password'      => Hash::make($validated['password']),
            'country_code'  => $validated['country_code'],
            'phone'         => $validated['phone'],
            'nationality'   => $validated['nationality'],
            'ubigeo'        => $validated['ubigeo'],
            'address'       => $validated['address'],
            'profession'    => $validated['profession'],
            'role'          => 'student',
        ]);

        Auth::login($user);
        return redirect()->route('student.dashboard')->with('success', '¡Registro exitoso!');
    }

    public function showLogin(): View {
        $enterprise = Enterprise::first();
        return view('student.auth.login', compact('enterprise'));
    }

    public function login(LoginValidate $request) {
        $validated = $request->validated();

        // Verificar si el usuario es administrador
        $user = User::where('email', $validated['email'])->first();

        if (!$user || $user->isAdmin()) {
            return back()->withErrors([
                'email' => 'Este usuario no tiene los permisos para el acceso por este formulario.',
            ])->onlyInput('email');

        }

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();
            if (Auth::user()->role == 'student') {
                return redirect()->intended('dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no son correctas.',
        ])->onlyInput('email');

    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
