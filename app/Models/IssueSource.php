<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon_slug',
        'status',
        'user_id',
    ];
}
