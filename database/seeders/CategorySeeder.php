<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Change Request',
            'initial' => 'CR',
            'slug' => 'change-request',
            'description' => 'Change Request',
            'requires_approval' => true,
        ]);

        Category::create([
            'name' => 'Incident',
            'initial' => 'INC',
            'slug' => 'incident',
            'description' => 'Incident',
        ]);

        Category::create([
            'name' => 'Training',
            'initial' => 'TR',
            'slug' => 'training',
            'description' => 'Training',
        ]);

        Category::create([
            'name' => 'New Feature Request',
            'initial' => 'FR',
            'slug' => 'new-feature-request',
            'description' => 'New Feature Request',
            'requires_approval' => true,
        ]);
    }
}
