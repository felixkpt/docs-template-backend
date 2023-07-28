<?php
$controller = "StickyNotesController@";
Route::get('/',$controller.'index');
Route::post('/',$controller.'storeStickyNote');
Route::get('/list',$controller.'listStickyNotes');
Route::get('/fetch',$controller.'fetchStickyNotes');
Route::get('/edit/{id}',$controller.'editNoteModal');
Route::delete('/delete/{stickynote}',$controller.'destroyStickyNote');
