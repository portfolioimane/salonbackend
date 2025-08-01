<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        Service::create([
            'name' => 'Haircut',
            'description' => 'Basic haircut and style.',
            'price' => 25.00,
            'image' => 'haircut.jpg',
            'duration' => 30,
            'category' => 'Hair',
            'featured' => true,
        ]);

        Service::create([
            'name' => 'Massage Therapy',
            'description' => 'Relaxing full-body massage.',
            'price' => 60.00,
            'image' => 'massage.jpg',
            'duration' => 60,
            'category' => 'Wellness',
            'featured' => false,
        ]);
    }
}
