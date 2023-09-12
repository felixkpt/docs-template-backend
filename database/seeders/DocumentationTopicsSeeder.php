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
            ['section_id' => 1, 'title' => 'Introduction'],
            ['section_id' => 1, 'title' => 'Components'],
            ['section_id' => 2, 'title' => 'Database Schema'],
        ];

        foreach ($topics as $topic) {
            $slug = Str::slug($topic['title']);
            DocumentationTopic::updateOrCreate(['slug' => $slug], ['section_id' => $topic['section_id'], 'title' => $topic['title'], 'slug' => $slug]);
        }
    }
}
