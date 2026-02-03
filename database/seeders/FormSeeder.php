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
                'description' => 'A formal proposal to modify a system, process, or configuration',
                'initial' => 'CR',
                'requires_approval' => true,
            ],
            [
                'name' => 'Incident',
                'slug' => 'incident',
                'description' => 'Something is broken or service is down and needs urgent fixing.',
                'initial' => 'INC',
            ],
            [
                'name' => 'Training',
                'slug' => 'training',
                'description' => 'Requests for knowledge transfer, workshops, or user education',
                'initial' => 'TR',
                'requires_approval' => true,
            ],
            [
                'name' => 'Inquiry',
                'slug' => 'inquiry',
                'description' => 'Requests for information, reports, documentation, clarification',
                'initial' => 'INQ',
            ]
        ];

        foreach ($forms as $form) {
            Form::createOrFirst($form);
        }
    }
}
