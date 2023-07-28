<?php
use App\Http\Controllers\Admin\Settings\SocialMedia\Whatsapp\WhatsAppSettingController;
use App\Http\Controllers\Admin\Settings\SocialMedia\Whatsapp\WhatsAppCannedResponseController;
use App\Http\Controllers\Admin\Settings\SocialMedia\Whatsapp\WhatsAppBusinessHourController;
use Illuminate\Support\Facades\Route;

$controller = WhatsAppSettingController::class;
$canned_controller = WhatsAppCannedResponseController::class;
$business_hours_controller = WhatsAppBusinessHourController::class;

Route::get('/whatsapp',[$controller,'index']);
Route::get('/whatsapp/auto-responses', [$controller, 'getBusinessHoursTemplate']);
Route::post('/whatsapp/auto-responses', [$controller, 'storeTemplateMessage']);
Route::get('/whatsapp/menus', [$controller, 'loadMenus']);
Route::post('/whatsapp/save-menu', [$controller, 'saveMenu']);
Route::get('/whatsapp/configs', [$controller, 'getConfigs']);
Route::post('/whatsapp/configs', [$controller, 'saveConfigs']);
Route::get('/whatsapp/queues', [$controller, 'getQueueUsers']);

Route::post('/whatsapp/queues/user/remove/{id}',[$controller,'removeQueueUser']);
Route::post('/whatsapp/queues', [$controller, 'saveQueueUser']);


Route::get('/whatsapp/canned-responses', [$canned_controller, 'listWhatsAppCannedResponses']);
Route::post('/whatsapp/canned-responses', [$canned_controller, 'storeWhatsAppCannedResponse']);

Route::get('/whatsapp/business-hours', [$business_hours_controller, 'listWhatsAppBusinessHours']);
Route::post('/whatsapp/business-hours', [$business_hours_controller, 'storeWhatsAppBusinessHour']);
