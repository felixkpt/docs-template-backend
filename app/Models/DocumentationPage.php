<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentationPage extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'slug', 'content_short', 'content', 'image', 'status_id', 'user_id'];

}
