<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'split_from_ticket_id',
        'merged_from_ticket_id',
        'ticket_status_id',
        'queue_id',
        'previous_ticket_status_id',
        'assigned_to',
        'department_id',
        'previous_department_id',
        'previous_assigned_to',
        'comment',
        'user_id',
        'status',
        'from_mail_scanner',
        'read_at',
        'first_read_by',
        'update_type',
    ];

    // Define the relationships with other models if needed
}
