<?php

namespace Database\Seeders;

use App\Models\DocumentationTopic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DocumentationTopicsSeeder extends Seeder
{
    public function run()
    {
        $topics = [
            ['category_id' => 1, 'title' => 'Introduction'],
            ['category_id' => 1, 'title' => 'Components'],
            ['category_id' => 2, 'title' => 'Database Schema'],
        ];

        foreach ($topics as $topic) {
            $slug = Str::slug($topic['title']);
            DocumentationTopic::updateOrCreate(['slug' => $slug], ['category_id' => $topic['category_id'], 'title' => $topic['title'], 'slug' => $slug]);
        }
    }
}
