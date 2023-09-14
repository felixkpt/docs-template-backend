<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentationTopic extends Model
{
    use HasFactory;
    protected $fillable = ['category_id', 'title', 'slug', 'description', 'image', 'status_id', 'user_id'];
}
