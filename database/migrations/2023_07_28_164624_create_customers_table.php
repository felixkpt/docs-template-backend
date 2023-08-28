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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('merchant_id')->nullable();
            $table->longText('email')->nullable();
            $table->unsignedInteger('customer_type_id')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedInteger('facebook_thread_id')->nullable();
            $table->string('facebook_id')->nullable();
            $table->unsignedBigInteger('alternate_phone')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->string('display_name')->nullable();
            $table->string('message_time')->nullable();
            $table->dateTime('fb_message_time')->nullable();
            $table->dateTime('tweet_message_time')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('business_name')->nullable();
            $table->unsignedInteger('facebook_page_id')->default(1);
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedTinyInteger('chat_tag_id')->nullable();
            $table->string('account_number')->nullable();
            $table->unsignedInteger('line_business_id')->nullable();
            $table->unsignedInteger('organization_type_id')->nullable();
            $table->unsignedInteger('order_frequency_id')->nullable();
            $table->unsignedInteger('order_day_id')->nullable();
            $table->string('contact_person')->nullable();
            $table->unsignedInteger('status_id')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
