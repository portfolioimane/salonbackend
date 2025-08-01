<?php
namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name' => 'Argan Oil Shampoo',
            'sku' => 'HC001',
            'category' => 'Hair Care',
            'supplier' => 'Beauty Supply Co.',
            'quantity' => 45,
            'price' => 120,
            'image' => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=300&h=300&fit=crop',
            'last_updated' => '2025-01-15',
        ]);
        // Add more products here...
    }
}
