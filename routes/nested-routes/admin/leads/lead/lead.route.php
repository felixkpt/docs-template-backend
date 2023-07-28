<?php
$controller = "LeadController@";
Route::get('/{id}', $controller . 'index');
Route::get('update/{id}', $controller . 'updateLead');
Route::get('edit-lead-product/{id}', $controller . 'editLeadProduct');
Route::post('update-lead-product', $controller . 'updateLeadProduct');
Route::post('/request-update', $controller . 'requestLeadUpdate');


Route::delete('/delete/{ticket}', $controller . 'destroyTicket');

$lead_update_controller = "LeadUpdateController@";
Route::get('/updates', $lead_update_controller . 'listLeadUpdates');
Route::post('/update', $lead_update_controller . 'storeLeadUpdate');

$controller1 = "LeadFilesController@";
Route::get('files/list/{id}', $controller1 . 'listLeadFiles');
Route::get('file/download/{id}', $controller1 . 'downloadFile');
