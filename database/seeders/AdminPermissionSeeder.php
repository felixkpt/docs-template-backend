<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AdminPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::updateOrCreate([
            'name' => 'admin_access',
            'user_id' => User::inRandomOrder()->first()->id,
            // 80% 1
            'status' => rand(0, 10) <= 8 ? 1 : 0,
        ]);
    }
}
