<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('documentation_categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('description')->nullable();
            $table->string('parent_category_id')->default(0);
            $table->string('image')->nullable();
            $table->unsignedInteger('priority_number')->default(9999);
            $table->unsignedInteger('status_id')->default(1);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documentation_categories');
    }
};
