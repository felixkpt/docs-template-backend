<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentationPage extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'slug', 'content_short', 'content', 'image', 'category_id', 'topic_id', 'status_id', 'user_id', 'priority_number'];

}
