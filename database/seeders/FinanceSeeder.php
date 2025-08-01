<?php
namespace Database\Seeders;

use App\Models\Finance;
use Illuminate\Database\Seeder;

class FinanceSeeder extends Seeder
{
    public function run(): void
    {
        Finance::insert([
            [
                'type' => 'revenue',
                'title' => 'Booking - Haircut',
                'amount' => 200,
                'date' => now()->subDays(2)->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'expense',
                'title' => 'Staff Salary',
                'amount' => 100,
                'date' => now()->subDays(1)->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
