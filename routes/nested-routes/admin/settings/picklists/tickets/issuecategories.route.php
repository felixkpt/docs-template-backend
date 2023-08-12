<?php

use App\Http\Controllers\Admin\Settings\Picklists\Tickets\IssueCategories\IssueCategoriesController;
use Illuminate\Support\Facades\Route;

$controller = IssueCategoriesController::class;

Route::get('/', [$controller, 'index'])->name('list issue categories');
