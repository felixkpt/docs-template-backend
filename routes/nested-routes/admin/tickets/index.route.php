<?php

use App\Http\Controllers\Admin\Tickets\Ticket\TicketController;
use Illuminate\Support\Facades\Route;

$controller = TicketController::class;
Route::get('/list', [$controller, 'index'])->name('All Tickets');
