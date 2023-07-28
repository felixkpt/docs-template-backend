<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

$controller = AdminController::class;

Route::get('/', [$controller, 'index']);
Route::get('/dashboard', [$controller, 'index']);
Route::get('/search', [$controller, 'search']);
Route::get('ticket-count-by-issue-sources', [$controller, 'getTicketsByIssueSource']);
Route::get('social-media-stats', [$controller, 'getSocialMediaStats']);
Route::get('tickets/stats', [$controller, 'getTicketsCount']);
Route::get('last-five-tickets', [$controller, 'getLastFiveTickets']);
Route::get('last-five-leads', [$controller, 'getLastFiveLeads']);
Route::get('tickets/status/stats', [$controller, 'getTicketStatusCounts']);
Route::get('tickets/channels/stats', [$controller, 'getTicketsChannelCounts']);
Route::get('tickets/categories/stats', [$controller, 'getTicketCategoriesCounts']);
Route::get('tickets-due-today', [$controller, 'ticketsDueToday']);
