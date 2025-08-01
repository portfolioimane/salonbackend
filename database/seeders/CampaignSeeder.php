<?php
// database/seeders/CampaignSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Campaign;

class CampaignSeeder extends Seeder {
    public function run() {
        Campaign::insert([
            [
                'name' => 'Spring Special 20% Off',
                'description' => 'Discount on all haircuts and color services during April',
                'start_date' => '2025-04-01',
                'end_date' => '2025-04-30',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Refer a Friend Bonus',
                'description' => 'Clients get a free manicure for every friend referred',
                'start_date' => '2025-03-15',
                'end_date' => '2025-05-15',
                'active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
