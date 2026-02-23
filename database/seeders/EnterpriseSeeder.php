<?php

namespace Database\Seeders;

use App\Models\Enterprise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnterpriseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Enterprise::create([
            'ruc'               => '20000000000',
            'company_name'      => 'IPF Consultores S.A.C.',
            'trade_name'        => 'IPF Educa',
            'legal_representative_dni' => '00000000',
            'legal_representative' => 'Francisco Llactas Flores',
            'address'           => 'JR. INMACULADA NRO. 399 (POR EL EX TRATRIL MUNICIPAL) UCAYALI - CORONEL PORTILLO - CALLERIA',
            'geographical_code' => '000006',
            'city'              => 'Pucallpa',
            'business_sector'   => 'Capacitación y Certificación',
            'phrase'            => 'Transformando la educación online con pasión, innovación y compromiso',
            'description'       => 'Somos una empresa que cumplimos las necesidades y expectativas de nuestros clientes manteniendo estándares de calidad, seguridad y salud en el trabajo y respetando el medio ambiente. Contamos con un equipo de profesionales altamente especializados y de gran experiencia. Nuestro equipo está en la capacidad de desarrollar cualquier servicio de alta complejidad técnica y geográfica.',
            'vision'            => 'Ser reconocidos como una empresa líder en la región donde operamos en el mercado de consultoría y servicios generales, destacándonos por la calidad de nuestros servicios.',
            'mission'           => 'Brindar servicios generales a fin de atender a a nuestros clientes cumplimiento los requerimientos legales de los diversos entes reguladores y fiscalizadores del Perú, de forma profesional y personalizada.',
            'phone_number_1'    => '948279231',
            'email'             => 'ipf-informes@ipf.com',
            'facebook_link'     => 'facebook.com/ipfconsultoresperu',
            'linkedin_link'     => 'linkedin.com/ipfconsultoresperu',
            'twitter_link'      => 'x.com/ipfconsultoresperu',
            'instagram_link'    => 'instagram.com/ipfconsultoresperu',
            'whatsapp_link'     => '51948279231',
            'logo_path'         => 'image.png',
            'favicon_path'      => 'image.png',
        ]);
    }
}
