<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('student.auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dni' => 'required|string|max:20|unique:users',
            'names' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'country_code' => 'required|string|max:5',
            'phone' => 'required|string|max:20',
            'nationality' => 'required|string|max:100',
            'ubigeo' => 'required|string|max:10',
            'address' => 'required|string|max:500',
            'profession' => 'required|string|max:255',
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();

        $user = User::create([
            'dni'           => $request->dni,
            'names'         => $request->names,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'country_code'  => $request->country_code,
            'phone'         => $request->phone,
            'nationality'   => $request->nationality,
            'ubigeo'        => $request->ubigeo,
            'address'       => $request->address,
            'profession'    => $request->profession,
            'role'          => 'student',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', '¡Registro exitoso!');
    }

    public function showLogin(): View {
        return view('student.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->intended('dashboard');
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
