<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // AdminUserSeeder::class,
            // PermissionSeeder::class,
            // RoleSeeder::class,
            // UsersTableSeeder::class,
            // CompanyTypeSeeder::class,
            CompaniesTableSeeder::class,
            // DepartmentSeeder::class,
            // IssueSourceSeeder::class,
            // IssueCategorySeeder::class,
            // DispositionSeeder::class,
            // SlaLevelSeeder::class,
            // QueueSeeder::class,
            // TicketStatusSeeder::class,

            CustomersTableSeeder::class,
            TicketSeeder::class,
            DocumentationSeeder::class,
        ]);

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
