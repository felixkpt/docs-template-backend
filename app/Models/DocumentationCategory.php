<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentationCategory extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'slug', 'image', 'status_id', 'user_id'];

}
