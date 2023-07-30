<?php 

use App\Http\Controllers\Admin\Documentations\DocumentationsController;
use Illuminate\Support\Facades\Route;

$controller = DocumentationsController::class;
Route::get('/', [$controller, 'index'])->name('documentations.index');
Route::get('/list', [$controller, 'list'])->name('documentations.list');
Route::get('/create', [$controller, 'create'])->name('documentations.create');
Route::post('/', [$controller, 'store'])->name('documentations.store');
