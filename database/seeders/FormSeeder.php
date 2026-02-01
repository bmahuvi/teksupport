<?php

namespace Database\Seeders;

use App\Models\Form;
use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $forms = [
            [
                'name' => 'Change Request',
                'slug' => 'change-request',
                'description' => 'A form for CR requests',
                'initial' => 'CR',
                'requires_approval' => true,
            ],
            [
                'name' => 'Incident',
                'slug' => 'incident',
                'description' => 'A form for incidents',
                'initial' => 'INC',
            ],
            [
                'name' => 'Training',
                'slug' => 'training',
                'description' => 'A form for trainings',
                'initial' => 'TR',
                'requires_approval' => true,
            ],
        ];

        foreach ($forms as $form) {
            Form::createOrFirst($form);
        }
    }
}
