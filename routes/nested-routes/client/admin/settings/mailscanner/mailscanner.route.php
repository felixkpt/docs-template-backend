<?php

use App\Http\Controllers\Admin\Settings\Mailscanner\TargetMailsController;
use Illuminate\Support\Facades\Route;

$controller = TargetMailsController::class;

Route::get('/',[$controller,'index']);
Route::post('targetmails',[$controller,'storeTargetMail']);
Route::get('targetmails/toggle-status',[$controller,'toggleTargetMailStatus']);
Route::get('targetmails/list/',[$controller,'listTargetMails']);
Route::delete('targetmails/delete/{id}',[$controller,'destroyTargetMail']);

Route::get('scannedmails',[$controller,'scannedMails']);
Route::get('mails/list',[$controller,'listScannedMails']);
Route::get('spammed_mails',[$controller,'spammedMails']);
Route::get('spammed_mails/list',[$controller,'listSpammedEmails']);
Route::post('spammed_mails/delete/{id}',[$controller,'deleteSpammedMail']);
Route::get('email-template',[$controller,'getScannedMails']);

