<?php

use App\Http\Controllers\Admin\Tickets\Ticket\Sla\SlaTicketController;
use Illuminate\Support\Facades\Route;

$controller = SlaTicketController::class;
Route::get('/{id}', [$controller, 'index'])->name('Sla ticket');
