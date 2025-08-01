<?php
// database/seeders/EmployeeSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        Employee::insert([
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@example.com',
                'role' => 'Senior Stylist',
                'phone' => '+1 555-0101',
                'avatar' => 'https://images.unsplash.com/photo-1494790108755-2616b612b786?w=150&h=150&fit=crop&crop=face',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael.chen@example.com',
                'role' => 'Hair Colorist',
                'phone' => '+1 555-0102',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop&crop=face',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Emma Williams',
                'email' => 'emma.williams@example.com',
                'role' => 'Nail Technician',
                'phone' => '+1 555-0103',
                'avatar' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=150&h=150&fit=crop&crop=face',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
