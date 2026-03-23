<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EnterpriseValidate;
use App\Models\Enterprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EnterpriseAdminController extends Controller {

    public function __construct() {
        $this->middleware(['auth:sanctum', 'admin', 'prevent.back']);
    }

    public function index() {
        $enterprise = Enterprise::first();

        if (!$enterprise) {
            $enterprise = new Enterprise();
        }

        return view('admin.enterprise.index', compact('enterprise'));
    }

    /**
     * Actualizar los datos de la empresa
     */
    public function update(EnterpriseValidate $request) {
        $validated = $request->validated();

        // Excluir todos los campos de archivo para no pisarlos con el fill()
        $data = $request->except(['logo', 'favicon', 'signature_photo']);

        $enterprise = Enterprise::first();

        if (!$enterprise) {
            $enterprise = new Enterprise();
        }

        // ── Procesar logo ────────────────────────────────────────────────────
        if ($request->hasFile('logo')) {
            $logoRaw = $enterprise->getAttributes()['logo_path'] ?? null;
            if ($logoRaw && Storage::exists($logoRaw)) {
                Storage::delete($logoRaw);
            }

            $logoPath = $request->file('logo')->store('public/enterprise');
            $data['logo_path'] = $logoPath;

            // Copia sincronizada en photos/ (compatibilidad con otras vistas)
            Storage::put(
                'public/photos/ipf-logo.png',
                file_get_contents($request->file('logo')->path())
            );
        }

        // ── Procesar favicon ─────────────────────────────────────────────────
        if ($request->hasFile('favicon')) {
            $faviRaw = $enterprise->getAttributes()['favicon_path'] ?? null;
            if ($faviRaw && Storage::exists($faviRaw)) {
                Storage::delete($faviRaw);
            }

            $faviconPath = $request->file('favicon')->store('public/enterprise');
            $data['favicon_path'] = $faviconPath;

            // Copia fija en raíz pública
            Storage::put(
                'public/favicon.ico',
                file_get_contents($request->file('favicon')->path())
            );
        }

        // ── Procesar firma ───────────────────────────────────────────────────
        if ($request->hasFile('signature_photo')) {
            // Eliminar archivo anterior
            $rawSig = $enterprise->getAttributes()['manager_signature'] ?? null;
            if ($rawSig && Storage::exists($rawSig)) {
                Storage::delete($rawSig);
            }

            // Nombre del archivo = slug del representante legal + extensión original
            $ext        = $request->file('signature_photo')->getClientOriginalExtension() ?: 'png';
            $repName    = $request->input('legal_representative');
            $fileName   = Str::slug($repName) . '.' . $ext;

            // Guardar con nombre personalizado
            $signaturePath = $request->file('signature_photo')->storeAs('public/enterprise', $fileName);
            $data['manager_signature'] = $signaturePath;

            // Copia con nombre fijo para compatibilidad con documentos/certificados
            Storage::put(
                'public/enterprise/manager_signature.png',
                file_get_contents($request->file('signature_photo')->path())
            );
        }

        $enterprise->fill($data);
        $enterprise->save();

        return redirect()->route('admin.enterprise.index')->with('success', 'Datos de la empresa actualizados correctamente.');
    }

    /**
     * Eliminar logo
     */
    public function deleteLogo() {
        $enterprise = Enterprise::first();

        if ($enterprise) {
            $logoRaw = $enterprise->getAttributes()['logo_path'] ?? null;
            if ($logoRaw && Storage::exists($logoRaw)) {
                Storage::delete($logoRaw);
            }
            $enterprise->logo_path = null;
            $enterprise->save();

            if (Storage::exists('public/photos/ipf-logo.png')) {
                Storage::delete('public/photos/ipf-logo.png');
            }
        }

        return redirect()->back()->with('success', 'Logo eliminado correctamente.');
    }

    /**
     * Eliminar favicon
     */
    public function deleteFavicon() {
        $enterprise = Enterprise::first();

        if ($enterprise) {
            $faviRaw = $enterprise->getAttributes()['favicon_path'] ?? null;
            if ($faviRaw && Storage::exists($faviRaw)) {
                Storage::delete($faviRaw);
            }
            $enterprise->favicon_path = null;
            $enterprise->save();

            if (Storage::exists('public/favicon.ico')) {
                Storage::delete('public/favicon.ico');
            }
        }

        return redirect()->back()->with('success', 'Favicon eliminado correctamente.');
    }
}