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
            ['email' => 'admin@recrutement.ma'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'role' => User::ROLE_ADMIN,
                'is_active' => true,
            ]
        );
    }
}
