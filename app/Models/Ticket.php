<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'split_from_ticket_id',
        'freshdesk_ticket_id',
        'merged_to_ticket_id',
        'customer_id',
        'issue_source_id',
        'issue_category_id',
        'disposition_id',
        'department_id',
        'assigned_to',
        'priority',
        'user_id',
        'ticket_status_id',
        'queue_id',
        'status',
        'fcr',
        'ticket_tat',
        'expected_resolution_time',
        'closed_at',
        'actual_resolution_time',
        'first_response_time',
        'sla_level_id',
        'creation_email_sent',
        'post_breach_reminder',
        'follow_up_reminder',
        'breached',
        'from_mail_scanner',
        'mail_direction',
        'subject',
        'is_spam',
        'is_historic',
        'ticket_source',
        'first_response_duration',
        'average_handling_time',
        'escalation_times',
        'resolved_at',
        'has_failed_mail_alert',
        'reopened',
    ];
}
