<?php

namespace App\Models;

use App\Traits\ExcludeSystemFillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory, ExcludeSystemFillable;
    protected $fillable = ['name', 'guard_name', 'parent_folder', 'uri', 'title', 'user_id', 'slug', 'icon', 'hidden'];
    protected $systemFillable = ['parent_folder', 'uri', 'title', 'slug', 'icon', 'hidden'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['parent_folder', 'uri', 'title', 'slug', 'icon', 'hidden'];
}
