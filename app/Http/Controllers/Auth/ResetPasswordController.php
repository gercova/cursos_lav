<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Enterprise;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    public function showResetForm(Request $request) {
        $token = $request->route('token');

        // 2. Usamos el modelo en lugar de app('enterprise')
        $enterprise = Enterprise::first() ?? (object) [
            'trade_name'    => 'Plataforma de Cursos',
            'logo_path'     => asset('images/logo.png'),
            'favicon_path'  => asset('favicon.ico'), // Agregué el favicon por si tu vista base lo pide
        ];

        return view('auth.reset-password', compact('enterprise'))->with(['token' => $token, 'email' => $request->email]);
    }

    protected function sendResetResponse(Request $request, $response) {
        return redirect()->route('login')->with('status', trans($response));
    }
}
