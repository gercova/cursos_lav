<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
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

        // Obtener la información de la empresa
        $enterprise = app('enterprise') ?? (object) [
            'trade_name'    => 'Plataforma de Cursos',
            'logo_path'     => asset('images/logo.png'),
        ];

        return view('auth.reset-password', compact('enterprise'))->with(['token' => $token, 'email' => $request->email]);
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(Request $request, $response) {
        return redirect()->route('login')->with('status', trans($response));
    }
}
