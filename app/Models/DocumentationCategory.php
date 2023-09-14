<?php

namespace App\Models;

use App\Traits\RemoveHiddenFromFillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentationCategory extends Model
{
    use HasFactory, RemoveHiddenFromFillable;
    protected $fillable = ['title', 'slug', 'description', 'image', 'status_id', 'user_id', 'parent_category_id', 'priority_number'];
    protected $hidden = ['parent_category_id'];
}
