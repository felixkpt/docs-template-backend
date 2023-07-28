<?php
use Illuminate\Support\Facades\Route;
$controller = "LeadContactController@";
Route::get('/',$controller.'index');
Route::post('/',$controller.'storeLeadContact');
Route::get('/list',$controller.'listLeadContacts');
Route::delete('/delete/{}',$controller.'destroyLeadContact');
