<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. PROVINSI JAWA TENGAH
        $jateng = Province::create(['name' => 'Jawa Tengah']);
        
        $banjarnegara = Regency::create(['province_id' => $jateng->id, 'name' => 'Kabupaten Banjarnegara']);
        District::create(['regency_id' => $banjarnegara->id, 'name' => 'Batur']);
        District::create(['regency_id' => $banjarnegara->id, 'name' => 'Pejawaran']);
        District::create(['regency_id' => $banjarnegara->id, 'name' => 'Purwanegara']);

        $banyumas = Regency::create(['province_id' => $jateng->id, 'name' => 'Kabupaten Banyumas']);
        District::create(['regency_id' => $banyumas->id, 'name' => 'Purwokerto Selatan']);
        District::create(['regency_id' => $banyumas->id, 'name' => 'Baturraden']);

        // 2. PROVINSI DKI JAKARTA
        $dki = Province::create(['name' => 'DKI Jakarta']);
        
        $jaksel = Regency::create(['province_id' => $dki->id, 'name' => 'Kota Jakarta Selatan']);
        District::create(['regency_id' => $jaksel->id, 'name' => 'Tebet']);
        District::create(['regency_id' => $jaksel->id, 'name' => 'Kebayoran Baru']);

        // 3. PROVINSI JAWA BARAT
        $jabar = Province::create(['name' => 'Jawa Barat']);
        
        $bandung = Regency::create(['province_id' => $jabar->id, 'name' => 'Kota Bandung']);
        District::create(['regency_id' => $bandung->id, 'name' => 'Coblong']);
        District::create(['regency_id' => $bandung->id, 'name' => 'Sumur Bandung']);
    }
}