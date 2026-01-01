<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordValidate;
use App\Http\Requests\UserValidate;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentProfileController extends Controller {

    public function __construct() {
        $this->middleware(['auth:sanctum', 'student', 'prevent.back']);
    }

    public function show(): View {
        $user = Auth::user();
        $user->load(['enrollments.course', 'certificates.course']);
        return view('student.profile', compact('user'));
    }

    public function update(UserValidate $request): RedirectResponse {
        $user = Auth::user();

        $data = $request->validated();

        // Manejar la subida de foto de perfil si existe
        if ($request->hasFile('profile_photo')) {
            // Eliminar foto anterior si existe
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo'] = $path;
        }

        $user->update($data);

        return redirect()->route('student.profile')
            ->with('success', 'Perfil actualizado correctamente.');
    }

    public function updatePassword(PasswordValidate $request): RedirectResponse {
        $user = Auth::user();

        // Verificar que la contraseña actual sea correcta
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
        }

        // Actualizar la contraseña
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('student.profile')
            ->with('success', 'Contraseña actualizada correctamente.');
    }

    public function updateProfilePhoto(Request $request): RedirectResponse {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Eliminar foto anterior si existe
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Guardar nueva foto
        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        $user->update(['profile_photo' => $path]);

        return redirect()->route('student.profile')
            ->with('success', 'Foto de perfil actualizada correctamente.');
    }

    public function deleteProfilePhoto(): RedirectResponse {
        $user = Auth::user();

        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
            $user->update(['profile_photo' => null]);

            return redirect()->route('student.profile')
                ->with('success', 'Foto de perfil eliminada correctamente.');
        }

        return redirect()->route('student.profile')
            ->with('error', 'No tienes una foto de perfil para eliminar.');
    }
}
