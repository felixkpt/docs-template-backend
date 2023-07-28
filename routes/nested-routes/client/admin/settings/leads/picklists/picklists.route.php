<?php
$controller = "LeadsController@";
Route::get('/',$controller.'index');
Route::post('/',$controller.'storeLead');
Route::get('/list',$controller.'listLeads');
Route::delete('/delete/{lead}',$controller.'destroyLead');
