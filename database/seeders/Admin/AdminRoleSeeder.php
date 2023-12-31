<?php

namespace Database\Seeders\Admin;

use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        $role_counts = Role::count();

        $role = Role::updateOrCreate(
            ['name' => 'Super Admin'],
            [
                'name' => 'Super Admin',
                'guard_name' => 'api',
                'user_id' => User::first()->id,
                'status_id' => 1,
            ]
        );

        User::first()->assignRole($role);

        if ($role_counts === 0) {

            try {
                // Delete the entire directory along with its contents
                Storage::deleteDirectory('system/roles');

                // Success message
                echo "The roles menu directory 'storage/app/system/roles/' and its contents have been deleted.\n";
            } catch (Exception $e) {
                // Handle any errors that may occur during the deletion process
                echo "An error occurred: " . $e->getMessage() . ".\n";
            }
        }
    }
}
