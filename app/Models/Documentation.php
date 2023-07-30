<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documentation extends Model
{
    protected $fillable = ['title', 'slug', 'content_short', 'content', 'image', 'status', 'user_id'];
}
