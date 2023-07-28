<?php

use App\Http\Controllers\Admin\Socialmedia\Whatsapp\WhatsAppController;
use Illuminate\Support\Facades\Route;

$controller = WhatsAppController::class;
Route::get('/',function (){
    return "Hello";
});
//Route::get('/',[$controller,'index']);
//Route::post('/',[$controller,'storeWhatsAppMessage']);
Route::get('/list',[$controller,'listWhatsAppMessages']);
Route::delete('/delete/{whatsappmessage}',[$controller,'destroyWhatsAppMessage']);
