<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStudentValidate;
use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {

    public function showRegister(): View {
        $entreprise = Enterprise::first();
        return view('student.auth.register', compact('entreprise'));
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

    public function login(Request $request) {
        $credentials = $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

        if (Auth::attempt($credentials)) {
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
