<?php

use App\Http\Controllers\Admin\Documentation\Detail\DocumentationDetailController;
use Illuminate\Support\Facades\Route;

$controller = DocumentationDetailController::class;
Route::get('/{id}', [$controller, 'show'])->name('documentation.show')->everyone(true);
Route::put('/{id}', [$controller, 'update'])->name('documentation.update');
Route::delete('/{id}', [$controller, 'destroy'])->name('destroy');
