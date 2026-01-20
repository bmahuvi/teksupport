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
                'name' => 'Open',
                'slug' => 'open',
                'color' => '#3b82f6', // Blue
                'is_default_for_new' => true,
                'order_column' => 1,
            ],
            [
                'name' => 'In Progress',
                'slug' => 'in-progress',
                'color' => '#f59e0b', // Amber
                'order_column' => 2,
            ],
            [
                'name' => 'Answered',
                'slug' => 'answered',
                'color' => '#22c55e', // Green
                'order_column' => 3,
            ],
            [
                'name' => 'Pending',
                'slug' => 'pending',
                'color' => '#8b5cf6', // Purple
                'order_column' => 4,
            ],
            [
                'name' => 'Resolved',
                'slug' => 'resolved',
                'color' => '#10b981', // Green
                'order_column' => 5,
            ],
            [
                'name' => 'Closed',
                'slug' => 'closed',
                'color' => '#6b7280', // Gray
                'is_closing_status' => true,
                'order_column' => 6,
            ],
            [
                'name' => 'Rejected',
                'slug' => 'rejected',
                'color' => '#ffc107',
                'order_column' => 7,
            ],
            [
                'name' => 'Cancelled',
                'slug' => 'cancelled',
                'color' => '#dc3545',
                'order_column' => 8,
            ],
            [
                'name' => 'Approved',
                'slug' => 'approved',
                'color' => '#10b981',
                'order_column' => 9,
            ],
            [
                'name' => 'Waiting Approval',
                'slug' => 'waiting-approval',
                'color' => '#28a745',
                'order_column' => 10,
            ],
            [
                'name' => 'Waiting Release',
                'slug' => 'waiting-release',
                'color' => '#28a745',
                'order_column' => 11,
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
