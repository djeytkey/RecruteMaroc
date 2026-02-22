<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SectorSeeder::class,
            RecruitmentPackSeeder::class,
        ]);

        User::updateOrCreate(
            ['email' => 'tarik.engineering@gmail.com'],
            [
                'name' => 'Tarik BOUKJIJ',
                'password' => bcrypt('123456789'),
                'role' => User::ROLE_ADMIN,
                'is_active' => true,
            ]
        );
    }
}
