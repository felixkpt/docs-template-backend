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
        Schema::create('issue_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('icon_slug', 255)->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_sources');
    }
};
