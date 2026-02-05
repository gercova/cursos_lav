<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStudentValidate;
use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    #protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }

    public function showRegister(): View {
        $enterprise = Enterprise::first();
        return view('auth.register', compact('enterprise'));
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
            'address'       => $validated['address'],
            'profession'    => $validated['profession'],
            'role'          => 'student',
        ]);

        Auth::login($user);
        return redirect()->route('student.dashboard')->with('success', '¡Registro exitoso!');
    }
}
