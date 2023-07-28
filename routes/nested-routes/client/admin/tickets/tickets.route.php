<?php

use App\Http\Controllers\Admin\Tickets\TicketsController;
use Illuminate\Support\Facades\Route;

$controller = TicketsController::class;
Route::get('/', [$controller, 'index']);
Route::get('/mail', [$controller, 'mailTickets']);
Route::any('/create', [$controller, 'createTicket']);
Route::any('/create/{phone}/{source}', [$controller, 'createTicket']);
Route::any('/create/{phone}', [$controller, 'createTicket']);
Route::any('/show-customer-bio-data/{id}', [$controller, 'showCustomerBioData']);
Route::get('/self', [$controller, 'self']);
Route::any('/customer/add', [$controller, 'saveCustomer']);
Route::any('/contact-search', [$controller, 'searchContacts']);
Route::post('/', [$controller, 'storeTicket']);
Route::get('/export', [$controller, 'exportTickets']);
Route::get('/list', [$controller, 'listTickets']);
Route::get('/list-filtered', [$controller, 'listTicketFilters']);
Route::get('/agent_handling_time', [$controller, 'returnAgentHandlingView']);
Route::get('/agent_responses', [$controller, 'returnAgentResponseView']);
Route::get('agent_first_responses/list', [$controller, 'listTicketsAgentResponses']);
Route::get('agent_handling_time/list', [$controller, 'listTicketsAgentHandlingTime']);
Route::get('/merged_tickets', [$controller, 'returnMergedTicketsView']);
Route::get('/list/merged_tickets', [$controller, 'listTickets']);
Route::get('outbound-mail', [$controller, 'createMailTicket']);
Route::get('outbound-mail/{id}', [$controller, 'createContactMailTicket']);
Route::post('outbound-mail', [$controller, 'storeMailTicket']);
Route::post('/import', [$controller, 'importTickets']);
Route::get('my-tickets', [$controller, 'myTickets']);
Route::post('upload-image', [$controller, 'uploadImage']);
