<?php

use App\Http\Controllers\Admin\Posts\Post\PostController;
use Illuminate\Support\Facades\Route;

$controller = PostController::class;
Route::get('/{id}', [$controller, 'index'])->name('Show post');
Route::delete('/{id}/update', [$controller, 'update'])->name('Update post');
