<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enterprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EnterpriseAdminController extends Controller {


    public function __construct() {
        $this->middleware(['auth:sanctum', 'admin', 'prevent.back']);
    }

    public function index() {
        $enterprise = Enterprise::first();

        if (!$enterprise) {
            // Crear un registro vacío si no existe
            $enterprise = new Enterprise();
        }

        return view('admin.enterprise.index', compact('enterprise'));
    }

    /**
     * Actualizar los datos de la empresa
     */
    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'ruc'                       => 'required|digits:11',
            'company_name'              => 'required|string|max:255',
            'trade_name'                => 'required|string|max:255',
            'legal_representative_dni'  => 'nullable|digits:8',
            'legal_representative'      => 'nullable|string|max:255',
            'address'                   => 'required|string|max:500',
            'geographical_code'         => 'nullable|string|max:10',
            'city'                      => 'required|string|max:100',
            'business_sector'           => 'nullable|string|max:255',
            'phrase'                    => 'nullable|string|max:500',
            'description'               => 'nullable|string',
            'vision'                    => 'nullable|string',
            'mission'                   => 'nullable|string',
            'phone_number_1'            => 'required|string|max:20',
            // 'phone_number_2'            => 'nullable|string|max:20',
            'email'                     => 'required|email|max:100',
            'facebook_link'             => 'nullable|url|max:255',
            'linkedin_link'             => 'nullable|url|max:255',
            'twitter_link'              => 'nullable|url|max:255',
            'instagram_link'            => 'nullable|url|max:255',
            'whatsapp_link'             => 'nullable|string|max:255',
            'logo'                      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon'                   => 'nullable|image|mimes:ico,png|max:1024',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->except(['logo', 'favicon']);

        // Buscar si ya existe un registro
        $enterprise = Enterprise::first();

        if (!$enterprise) {
            $enterprise = new Enterprise();
        }

        // Procesar logo
        if ($request->hasFile('logo')) {
            // Eliminar logo anterior si existe
            if ($enterprise->logo_path && Storage::exists($enterprise->logo_path)) {
                Storage::delete($enterprise->logo_path);
            }

            $logoPath = $request->file('logo')->store('public/enterprise');
            $data['logo_path'] = $logoPath;

            // También actualizar el favicon en storage/photos/
            $logoContent = file_get_contents($request->file('logo')->path());
            Storage::put('public/photos/ipf-logo.png', $logoContent);
        }

        // Procesar favicon
        if ($request->hasFile('favicon')) {
            // Eliminar favicon anterior si existe
            if ($enterprise->favicon_path && Storage::exists($enterprise->favicon_path)) {
                Storage::delete($enterprise->favicon_path);
            }

            $faviconPath = $request->file('favicon')->store('public/enterprise');
            $data['favicon_path'] = $faviconPath;

            // Copiar al directorio raíz como favicon.ico
            $faviconContent = file_get_contents($request->file('favicon')->path());
            Storage::put('public/favicon.ico', $faviconContent);
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

        if ($enterprise && $enterprise->logo_path && Storage::exists($enterprise->logo_path)) {
            Storage::delete($enterprise->logo_path);
            $enterprise->logo_path = null;
            $enterprise->save();

            // También eliminar el logo de photos
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

        if ($enterprise && $enterprise->favicon_path && Storage::exists($enterprise->favicon_path)) {
            Storage::delete($enterprise->favicon_path);
            $enterprise->favicon_path = null;
            $enterprise->save();

            // También eliminar el favicon de la raíz
            if (Storage::exists('public/favicon.ico')) {
                Storage::delete('public/favicon.ico');
            }
        }

        return redirect()->back()->with('success', 'Favicon eliminado correctamente.');
    }
}
