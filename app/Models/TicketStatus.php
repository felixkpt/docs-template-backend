<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketStatus extends Model
{

    const CLOSED = 3;
    const RESOLVED = 2;
    const IN_PROGRESS = 7;
    const OPEN = 1;
    const REOPENED = 6;
    const PENDING_INTERNALLY = 4;
    const PENDING_EXTERNALLY = 5;

    protected $fillable = [
        'name',
        'user_id',
        'status',
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
