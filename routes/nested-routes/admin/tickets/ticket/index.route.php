<?php

use App\Http\Controllers\Admin\Tickets\Ticket\TicketController;
use Illuminate\Support\Facades\Route;

$controller = TicketController::class;
Route::get('/{id}', [$controller, 'index'])->name('Single Ticket');
