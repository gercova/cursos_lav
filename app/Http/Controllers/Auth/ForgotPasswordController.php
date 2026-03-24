<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Enterprise;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function showLinkRequestForm(): View {
        $enterprise = Enterprise::first();
        return view('auth.forgot-password', compact('enterprise'));
    }

    public function sendResetLinkEmail(Request $request) {
        $this->validateEmail($request);

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT ? $this->sendResetLinkResponse($request, $response) : $this->sendResetLinkFailedResponse($request, $response);
    }

    protected function sendResetLinkResponse(Request $request, $response) {
        return back()->with('status', trans($response));
    }

    protected function sendResetLinkFailedResponse(Request $request, $response) {
        return back()->withInput($request->only('email'))->withErrors(['email' => trans($response)]);
    }

    // Aquí va la validación correcta papu
    protected function validateEmail(Request $request) {
        $request->validate(
            ['email' => 'required|email|exists:users,email'],
            ['email.exists' => 'No encontramos ninguna cuenta registrada con este correo electrónico.']
        );
    }
}
