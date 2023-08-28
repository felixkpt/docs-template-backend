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
        Schema::create('ticket_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('split_from_ticket_id')->nullable();
            $table->unsignedBigInteger('merged_from_ticket_id')->nullable();
            $table->unsignedInteger('ticket_status_id');
            $table->unsignedBigInteger('queue_id')->nullable();
            $table->unsignedInteger('previous_ticket_status_id')->default(0);
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('previous_department_id')->nullable();
            $table->unsignedBigInteger('previous_assigned_to')->default(0);
            $table->longText('comment')->nullable();
            $table->timestamps();
            $table->unsignedTinyInteger('from_mail_scanner')->default(0);
            $table->dateTime('read_at')->nullable();
            $table->unsignedBigInteger('first_read_by')->nullable();
            $table->unsignedTinyInteger('update_type')->default(1);
            $table->unsignedInteger('status_id')->default(1);
            $table->unsignedBigInteger('user_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_updates');
    }
};
