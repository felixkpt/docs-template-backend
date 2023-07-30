<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Documentation;
use App\Models\User;
use App\Services\Client;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class DocumentationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Set the total number you want to seed
        $totalRecords = 10100;
        $batchSize = 1000; // Set the desired batch size

        if (Documentation::count() >= $totalRecords) return;

        $totalBatches = ceil($totalRecords / $batchSize);

        for ($batch = 1; $batch <= $totalBatches; $batch++) {
            $start = ($batch - 1) * $batchSize + 1;
            $end = min($batch * $batchSize, $totalRecords);

            $this->seedBatch($start, $end);

            // A moment of rest for the seeder before proceeding to the next batch 😴💤
            sleep(10);
        }
    }

    /**
     * Seed a batch of records.
     *
     * @param int $start
     * @param int $end
     * @return void
     */
    private function seedBatch($start, $end)
    {
        $faker = Faker::create();

        for ($i = $start; $i <= $end; $i++) {
            $title = $faker->sentence();
            $slug = Str::slug($title);
            $paragraphs = $faker->paragraphs(rand(3, 10)); // Generates 3 to 10 paragraphs

            // Add <p> tags to each paragraph
            $formattedParagraphs = array_map(function ($paragraph) {
                return '<p>' . $paragraph . '</p>';
            }, $paragraphs);

            // Join paragraphs into a single string
            $content = implode('', $formattedParagraphs);

            $unformattedParagraphs = implode(' ', $paragraphs);

            // Find the last space before the 200th character
            $lastSpace = strrpos(substr($unformattedParagraphs, 0, 200), ' ');

            if ($lastSpace !== false) {
                // Truncate the content at the last space position
                $contentShort = substr($unformattedParagraphs, 0, $lastSpace + 1);
            } else {
                // If no space is found, simply take the first 200 characters
                $contentShort = substr($unformattedParagraphs, 0, 200);
            }

            // Random status (70% chance of being published)
            $status = rand(0, 10) <= 7 ? 'published' : Arr::random(['draft', 'pending_review', 'scheduled', 'published', 'private', 'trash', 'archived', 'draft_in_review', 'rejected']);

            // Random user association
            $userId = User::inRandomOrder()->first()->id;

            // Create the documentation
            $documentation = Documentation::updateOrCreate(['title' => $title], [
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'content_short' => $contentShort,
                'status' => $status,
                'user_id' => $userId,
                'created_at' => Carbon::now()->subMinutes(rand(0, 1440 * 90)), // Subtract a random number of minutes (up to 24 hours * x days)
                'updated_at' => Carbon::now(),
            ]);

            // Generate attachments (files) for 30% of the documentation (you can adjust this percentage as needed)
            if (rand(1, 100) <= 30) {
                $this->saveDocumentationFiles($documentation);
            }

            if ($documentation->wasRecentlyCreated) {

                $this->saveImage($documentation);
            }
        }
    }

    /**
     * Get attachments for a documentation and return the file path.
     *
     * @param int $documentationId
     * @param string $attachmentType
     * @return string|null
     */
    private function saveDocumentationFiles($documentation)
    {
        $randomNumber = rand(1, 100); // Generate a random number between 1 and 100.

        if ($randomNumber <= 50) {
            // Generate 1 attachment
            $this->generateAttachment($documentation);
        } elseif ($randomNumber <= 70) {
            // Generate 2 attachments
            $this->generateAttachment($documentation);
            $this->generateAttachment($documentation);
        } elseif ($randomNumber <= 80) {
            // Generate 3 attachments
            $this->generateAttachment($documentation);
            $this->generateAttachment($documentation);
            $this->generateAttachment($documentation);
        } else {
            // Generate 4 attachments
            $this->generateAttachment($documentation);
            $this->generateAttachment($documentation);
            $this->generateAttachment($documentation);
            $this->generateAttachment($documentation);
        }

        return true;
    }

    /**
     * Generate a single attachment for the documentation and save it.
     *
     * @param int $documentationId
     * @param string $attachmentType
     * @return void
     */
    private function generateAttachment($documentation)
    {
        // Get the current date using Carbon
        $now = Carbon::now();

        // Define the directory where the attachments will be saved
        $attachmentFolder = 'documentations/' . $now->year . '/' . $now->month . '/' . $now->day;

        $attachmentFilename = 'documentation_' . $documentation->id . '_' . now()->format('Ymd_His') . '.jpg'; // Use the desired file extension

        $dimensions = Arr::random([
            '200/300', '300/400', '200', '300', '400', '500', '700', '1200',
            '800/600', '600/800', '800', '600', '1000', '900', '1024/768',
            '1600/900', '1920/1080', '1280/720', '1366/768'
        ]);

        Client::downloadFileFromUrl('https://picsum.photos/' . $dimensions . '', $attachmentFolder, $attachmentFilename, $documentation);
    }

    function saveImage($documentation)
    {

        // Get the current date using Carbon
        $now = Carbon::now();

        // Define the directory where the attachments will be saved
        $attachmentFolder = 'documentations/' . $now->year . '/' . $now->month . '/' . $now->day;

        $attachmentFilename = 'documentation_' . $documentation->id . '_' . now()->format('Ymd_His') . '.jpg'; // Use the desired file extension


        $dimensions = Arr::random([
            '200/300', '300/400', '200', '300', '400', '500', '700', '1200',
            '800/600', '600/800', '800', '600', '1000', '900', '1024/768',
            '1600/900', '1920/1080', '1280/720', '1366/768'
        ]);

        $image = Client::downloadFileFromUrl('https://picsum.photos/' . $dimensions . '', $attachmentFolder, $attachmentFilename, null);

        $documentation->image = $image;
        $documentation->save();
    }
}
