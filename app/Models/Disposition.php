<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'priority_id',
        'user_id',
        'status',
        'fcr',
    ];

}
