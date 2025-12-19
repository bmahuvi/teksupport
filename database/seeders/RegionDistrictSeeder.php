<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionDistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            'Dar es Salaam' => ['Ilala', 'Kinondoni', 'Temeke', 'Ubungo', 'Kigamboni'],
            'Dodoma' => ['Dodoma', 'Bahi', 'Chamwino', 'Chemba', 'Kondoa', 'Kongwa', 'Mpwapwa'],
            'Mbeya' => ['Mbeya', 'Rungwe', 'Kyela', 'Mbarali', 'Chunya', 'Busokelo'],
            'Mwanza' => ['Ilemela', 'Nyamagana', 'Sengerema', 'Ukerewe', 'Magu', 'Misungwi', 'Kwimba'],
            'Arusha' => ['Arusha', 'Meru', 'Monduli', 'Longido', 'Ngorongoro'],
            'Kigoma' => ['Kigoma', 'Kasulu', 'Kakonko', 'Uvinza', 'Buhigwe', 'Kibondo'],
            'Mtwara' => ['Mtwara', 'Masasi', 'Nanyumbu', 'Newala', 'Tandahimba', 'Mtwara Vijijini'],
            'Kilimanjaro' => ['Moshi', 'Hai', 'Siha', 'Rombo', 'Mwanga', 'Same'],
            'Morogoro' => ['Morogoro', 'Kilombero', 'Mvomero', 'Gairo', 'Malinyi', 'Ulanga'],
            'Tanga' => ['Tanga', 'Muheza', 'Korogwe', 'Pangani', 'Handeni', 'Lushoto', 'Mkinga'],
            'Singida' => ['Singida', 'Ikungi', 'Manyoni', 'Mkalama', 'Iramba'],
            'Tabora' => ['Tabora', 'Nzega', 'Igunga', 'Urambo', 'Kaliua', 'Sikonge'],
            'Shinyanga' => ['Shinyanga', 'Kahama', 'Kishapu', 'Ushetu'],
            'Simiyu' => ['Bariadi', 'Maswa', 'Meatu', 'Itilima'],
            'Kagera' => ['Kagera', 'Biharamulo', 'Bukoba', 'Karagwe', 'Missenyi', 'Ngara', 'Rulenge'],
            'Mara' => ['Mara', 'Musoma', 'Butiama', 'Serengeti', 'Rorya', 'Tarime'],
            'Geita' => ['Geita', 'Bukombe', 'Chato', 'Mbogwe', 'Nkome'],
            'Manyara' => ['Babati', 'Hanang', 'Kiteto', 'Mbulu', 'Simanjiro'],
            'Lindi' => ['Lindi', 'Kilwa', 'Liwale', 'Nachingwea', 'Ruangwa'],
            'Rukwa' => ['Sumbawanga', 'Kalambo', 'Nkasi', 'Sumbawanga Urban'],
            'Ruvuma' => ['Songea', 'Mbinga', 'Namtumbo', 'Tunduru', 'Ludewa'],
            'Katavi' => ['Mpanda', 'Mlele', 'Nsimbo'],
            'Songwe' => ['Tunduma', 'Mbozi', 'Momba', 'Songwe'],
            'Iringa' => ['Iringa', 'Kilolo', 'Mafinga', 'Mufindi'],
            'Pwani' => ['Kibaha', 'Bagamoyo', 'Kisarawe', 'Mkuranga', 'Rufiji', 'Mafia'],
        ];

        foreach ($regions as $regionName => $districts) {
            $region = Region::firstOrCreate(['name' => $regionName]);

            foreach ($districts as $districtName) {
                $region->districts()->firstOrCreate(['name' => $districtName]);
            }
        }
    }
}
