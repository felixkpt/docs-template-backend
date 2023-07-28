<?php

use App\Http\Controllers\Admin\Reports\ReportsController;
use Illuminate\Support\Facades\Route;

$controller = ReportsController::class;
Route::get('/', [$controller, 'index']);

Route::get('/ticketstatuses/stats', [$controller, 'ticketCountByStatus']);
Route::get('/issuecategories/stats', [$controller, 'ticketCountByIssueCategory']);
Route::get('/issues-sources/stats', [$controller, 'ticketCountByIssueSources']);
Route::get('/ticketpriorities/stats', [$controller, 'ticketCountByPriority']);
Route::get('ticket-breakdown', [$controller, 'getTicketsCountStatsByDay']);
Route::get('/categories/stats',[$controller,'countTicketsByStatus']);

Route::get('/agent_performance/stats', [$controller, 'getAgentPerformanceReport']);
Route::get('/agent_handling_time/stats', [$controller, 'getAgentHandlingTime']);
Route::get('/agent_response_time/stats', [$controller, 'getAgentResponseTime']);

Route::get('/ticket_channel_trend', [$controller, 'getTicketsCountStatusByDayofWeek']);
Route::get('/hourly_ticket_trend', [$controller, 'getTicketsCountStatusByHour']);
Route::get('/ticket_department_status/stats', [$controller, 'getDepartmentTicketCount']);

Route::get('/assignment-view', [$controller, 'returnAssignmentView']);
Route::get('/assignment/list', [$controller, 'listAssignmentTickets']);
Route::get('/sla/stats', [$controller, 'getSlaReport']);
