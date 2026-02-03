<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\District;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $district = District::where('name', 'Ubungo')->first();

        Company::create([
            'name' => 'TekTek Solutions',
            'region_id' => $district->region->id,
            'district_id' => $district->id,
            'email' => 'info@tektek.co.tz',
            'phone' => '0786670698',
            'is_active' => true,
            'is_main' => true,
            'ulid' => strtolower(Str::ulid())
        ]);
    }
}
