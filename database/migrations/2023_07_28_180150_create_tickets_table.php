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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('split_from_ticket_id')->nullable();
            $table->unsignedBigInteger('freshdesk_ticket_id')->nullable();
            $table->unsignedBigInteger('merged_to_ticket_id')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedInteger('issue_source_id')->nullable();
            $table->unsignedInteger('issue_category_id')->nullable();
            $table->unsignedInteger('disposition_id')->nullable();
            $table->unsignedInteger('department_id')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedTinyInteger('priority');
            $table->unsignedInteger('ticket_status_id');
            $table->unsignedBigInteger('queue_id')->nullable();
            $table->unsignedTinyInteger('fcr')->default(1);
            $table->unsignedBigInteger('ticket_tat')->nullable();
            $table->timestamps();
            $table->datetime('expected_resolution_time')->nullable();
            $table->datetime('closed_at')->nullable();
            $table->datetime('actual_resolution_time')->nullable();
            $table->datetime('first_response_time')->nullable();
            $table->unsignedBigInteger('sla_level_id')->default(1);
            $table->unsignedTinyInteger('creation_email_sent')->default(0);
            $table->unsignedTinyInteger('post_breach_reminder')->default(0);
            $table->unsignedTinyInteger('follow_up_reminder')->default(0);
            $table->unsignedTinyInteger('breached')->default(0);
            $table->unsignedTinyInteger('from_mail_scanner')->default(0);
            $table->unsignedTinyInteger('mail_direction')->nullable();
            $table->longText('subject')->nullable();
            $table->unsignedTinyInteger('is_spam')->default(0);
            $table->unsignedTinyInteger('is_historic')->default(0);
            $table->unsignedTinyInteger('ticket_source')->default(1);
            $table->unsignedBigInteger('first_response_duration')->nullable();
            $table->unsignedInteger('average_handling_time')->nullable();
            $table->string('escalation_times')->nullable();
            $table->datetime('resolved_at')->nullable();
            $table->unsignedTinyInteger('has_failed_mail_alert')->default(0);
            $table->unsignedTinyInteger('reopened')->default(0);
            $table->unsignedInteger('status_id')->default(1);
            $table->unsignedBigInteger('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
