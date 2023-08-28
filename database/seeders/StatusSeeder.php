<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    public function run()
    {
        $statusNames = [
            'active', 'in_active',
            'draft', 'pending_review', 'scheduled', 'published',
            'private', 'trash', 'archived', 'draft_in_review', 'rejected'
        ];

        foreach ($statusNames as $name) {
            Status::updateOrCreate([
                'name' => $name,
                'description' => ucfirst(str_replace('_', ' ', $name)) . ' status.',
            ]);
        }
    }
}
