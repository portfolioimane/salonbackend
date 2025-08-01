<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BusinessHours;

class BusinessHoursSeeder extends Seeder
{
    public function run()
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        foreach ($days as $day) {
            BusinessHours::create([
                'day' => $day,
                'open_time' => '09:00',
                'close_time' => '17:00',
                'is_closed' => false,
            ]);
        }
    }
}