<?php

use App\Http\Controllers\Admin\Socialmedia\Twitterv1\Twitterv1Controller;
use Illuminate\Support\Facades\Route;

$controller = Twitterv1Controller::class;
Route::get('/',[$controller,'index']);
Route::post('/',[$controller,'store']);
Route::get('/list',[$controller,'list']);
Route::delete('/delete/{}',[$controller,'destroy']);
