<?php

use App\Http\Controllers\Admin\Socialmedia\Facebookv1\Facebookv1Controller;

$facebook_controller = Facebookv1Controller::class;

Route::get('/',[$facebook_controller,'index']);
Route::get('/chat-contacts', [$facebook_controller,'getChatContacts']);
Route::get('/chat-history/{id}', [$facebook_controller,'getChatHistory']);
Route::get('/history/{id}', [$facebook_controller,'getChatHistory2']);
Route::get('/load-notes/{id}', [$facebook_controller,'loadFacebookNotes']);
Route::post('/set-chat-as-read/{id}', [$facebook_controller,'setChatAsRead']);
Route::get('/new-chats/{id}', [$facebook_controller,'getNewChats']);
Route::get('/fetch-message/{contact_id}/{message_id}', [$facebook_controller,'getNewMessage']);
Route::get('/chat-reply', [$facebook_controller,'chatReply']);
Route::post('/chat-reply', [$facebook_controller,'chatReply']);
Route::get('/contact/{id}', [$facebook_controller,'getContact']);
Route::get('/all-contacts', [$facebook_controller,'getAllContacts']);
Route::post('/upload-attachment', [$facebook_controller,'uploadAttachment']);
Route::get('/my-chats-contacts', [$facebook_controller,'myChatsContacts']);
Route::get('/confirm-is-agent', [$facebook_controller,'confirmIsAgent']);
Route::post('notes', [$facebook_controller,'saveFacebookNotes']);
Route::get('setpage/{id}', [$facebook_controller,'setFbPage']);
Route::post('tmp', [$facebook_controller,'saveTmpFiles']);
Route::post('tmp/delete', [$facebook_controller,'deleteTmpFiles']);
//Canned Responses Routes
Route::get('/fetch-canned-responses', [$facebook_controller, 'cannedResponses']);
Route::get('/fetch-canned-response', [$facebook_controller, 'cannedResponse']);

Route::get('/contact/unread-count/{contactId}',[$facebook_controller,'getContactCounts']);
Route::get('/tabcounts',[$facebook_controller,'getTabCounts']);
Route::get('/pagestrip',[$facebook_controller,'renderPageStrip']);
