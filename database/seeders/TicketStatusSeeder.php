<?php

namespace Database\Seeders;

use App\Models\TicketStatus;
use Illuminate\Database\Seeder;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'New',
                'slug' => 'new',
                'color' => '#3b82f6', // Blue
                'is_default_for_new' => true,
                'order_column' => 1,
            ],
            [
                'name' => 'Pending',
                'slug' => 'pending',
                'color' => '#8b5cf6', // Purple
                'order_column' => 2,
            ],
            [
                'name' => 'Closed',
                'slug' => 'closed',
                'color' => '#6b7280', // Gray
                'is_closing_status' => true,
                'order_column' => 5,
            ],
            [
                'name' => 'Cancelled',
                'slug' => 'cancelled',
                'color' => '#dc3545',
                'order_column' => 7,
            ],
            [
                'name' => 'Approved',
                'slug' => 'approved',
                'color' => '#10b981',
                'order_column' => 4,
            ],
            [
                'name' => 'Waiting Approval',
                'slug' => 'waiting-approval',
                'color' => '#28a745',
                'order_column' => 3,
            ],
            [
                'name' => 'Waiting Release',
                'slug' => 'waiting-release',
                'color' => '#28a745',
                'order_column' => 6,
                'is_active' => false
            ],
        ];

        foreach ($statuses as $status) {
            TicketStatus::query()->updateOrCreate(
                ['slug' => $status['slug']],
                $status
            );
        }
    }
}
