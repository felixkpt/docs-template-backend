<?php

use App\Http\Controllers\Admin\Settings\SocialMedia\Whatsapp\WhatsAppBusinessHourController;
use Illuminate\Support\Facades\Route;

$controller = WhatsAppBusinessHourController::class;
Route::get('/',[$controller,'index']);
Route::post('/',[$controller,'storeWhatsAppBusinessHour']);
Route::get('/list',[$controller,'listWhatsAppBusinessHours']);
Route::delete('/delete/{whatsappbusinesshour}',[$controller,'destroyWhatsAppBusinessHour']);
