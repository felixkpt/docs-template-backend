<?php
use App\Http\Controllers\Admin\Settings\SocialMedia\Whatsapp\WhatsAppSettingController;
use Illuminate\Support\Facades\Route;

$controller = WhatsAppSettingController::class;
Route::get('/',function (){
    // Developer, please, please. Toka hapa. Go to socialmedia.route
    dd('it works only here ');
});

