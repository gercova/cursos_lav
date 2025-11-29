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
        Enterprise::created([
            'ruc'               => '20000000000',
            'company_name'      => 'IPF Consultores S.A.C.',
            'trade_name'        => 'IPF Consultores',
            'legal_representative_dni' => '00000000',
            'legal_representative' => 'Francisco Llactas Flores',
            'address'           => 'Av. Los Pinos #1200',
            'geographical_code' => '000006',
            'city'              => 'Chanchamayo',
            'business_sector'   => 'Capacitación y Certificación',
            'phrase'            => 'Transformando la educación online con pasión, innovación y compromiso',
            'description'       => '',
            'vision'            => '',
            'mission'           => '',
            'phone_number_1'    => '999999999',
            'phonen_umber_2'    => '999999999',
            'email'             => 'ipf-informes@ipf.com',
            'facebook_link'     => 'facebook.com/ipfconsultoresperu',
            'linkedin_link'     => 'linkedin.com/ipfconsultoresperu',
            'twitter_link'      => 'x.com/ipfconsultoresperu',
            'instagram_link'    => 'instagram.com/ipfconsultoresperu',
            'whatsapp_link'     => '999999999',
        ]);
    }
}
