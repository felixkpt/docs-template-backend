<?php

use App\Http\Controllers\Admin\Posts\Detail\PostDetailController;
use Illuminate\Support\Facades\Route;

$controller = PostDetailController::class;
Route::get('/{id}', [$controller, 'index'])->name('Show post');
Route::delete('/{id}/update', [$controller, 'update'])->name('Update post');
