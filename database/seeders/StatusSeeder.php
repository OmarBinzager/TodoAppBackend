<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'Not Started',
                'color' => 'Red' // Bootstrap gray color
            ],
            [
                'name' => 'In Progress',
                'color' => 'Blue' // Bootstrap primary color
            ],
            [
                'name' => 'Completed',
                'color' => 'Green' // Bootstrap success color
            ]
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
} 