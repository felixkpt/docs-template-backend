<?php
use App\Http\Controllers\Admin\Settings\Debts\DebtsController;

use Illuminate\Support\Facades\Route;
$controller = DebtsController::class;
Route::get('/',[$controller,'index']);

