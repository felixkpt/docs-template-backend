<?php

use App\Http\Controllers\Admin\Settings\Picklists\Tickets\IssueSources\IssueSourcesController;
use Illuminate\Support\Facades\Route;

$controller = IssueSourcesController::class;

Route::get('/', [$controller, 'index'])->name('list issue sources');
