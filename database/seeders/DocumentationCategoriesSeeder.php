<?php

namespace Database\Seeders;

use App\Models\DocumentationCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DocumentationCategoriesSeeder extends Seeder
{
    public function run()
    {
        $sections = [
            ['title' => 'General Usage'],
            ['title' => 'Frontend Documentation'],
            ['title' => 'API Documentation'],
            ['title' => 'Backend Documentation'],
        ];

        foreach ($sections as $section) {
            $slug = Str::slug($section['title']);
            DocumentationCategory::updateOrCreate(['slug' => $slug], ['title' => $section['title'], 'slug' => $slug]);
        }
    }
}
