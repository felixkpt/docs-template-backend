<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documentation_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('documentation_categories');
            $table->unsignedBigInteger('topic_id');
            $table->foreign('topic_id')->references('id')->on('documentation_topics');
            $table->string('title');
            $table->string('slug');
            $table->string('content_short')->nullable();
            $table->longText('content');
            $table->string('image')->nullable();
            $table->unsignedInteger('status_id')->default(1);
            $table->bigInteger('user_id')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentation_pages');
    }
};
