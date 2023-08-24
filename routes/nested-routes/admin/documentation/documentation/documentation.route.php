<?php

use App\Http\Controllers\Admin\Documentation\Documentation\DocumentationController;
use Illuminate\Support\Facades\Route;

$controller = DocumentationController::class;
Route::get('/{slug}', [$controller, 'show'])->name('View documentation')->everyone(true);
Route::put('/{id}', [$controller, 'update'])->name('documentation.update');
Route::delete('/{id}', [$controller, 'destroy'])->name('destroy');
