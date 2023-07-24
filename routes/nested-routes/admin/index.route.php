<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AdminController::class, 'index'])->name('Dashboard');
Route::get('/tickets', [AdminController::class, 'ticketsDash'])->name('Tickets dashboard');
