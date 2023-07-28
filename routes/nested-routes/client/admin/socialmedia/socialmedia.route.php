<?php

use Whatsapp\WhatsAppGroupController;
use Whatsapp\WhatsAppController;
use Whatsapp\WhatsAppCannedResponseController;
use Whatsapp\WhatsAppNoteController;
use Illuminate\Support\Facades\Route;

Route::get('/',function (){
    return "Hello";
});


$whatsapp_controller = WhatsAppController::class;
$whatsapp_group_controller = WhatsAppGroupController::class;
$whatsapp_note_controller = WhatsAppNoteController::class;
//$whatsapp_canned_responses_controller = WhatsAppCannedResponseController::class;

Route::get('/whatsapp', $whatsapp_controller.'@index');
Route::get('/whatsapp/chat-contacts', $whatsapp_controller.'@getChatContacts');
Route::get('/whatsapp/chat-history/{id}', $whatsapp_controller.'@getChatHistory');
Route::get('/whatsapp/set-chat-as-read/{id}', $whatsapp_controller.'@setChatAsRead');
Route::get('/whatsapp/new-chats/{id}', $whatsapp_controller.'@getNewChats');
Route::get('/whatsapp/chat-reply', $whatsapp_controller.'@chatReply');
Route::get('/whatsapp/contact/{id}', $whatsapp_controller.'@getContact');
Route::get('/whatsapp/all-contacts', $whatsapp_controller.'@getAllContacts');
Route::post('/whatsapp/upload-attachment', $whatsapp_controller.'@uploadAttachment');
Route::get('/whatsapp/my-chats-contacts', $whatsapp_controller.'@myChatsContacts');
Route::get('/whatsapp/confirm-is-agent', $whatsapp_controller.'@confirmIsAgent');
Route::get('/whatsapp/resolve-thread', $whatsapp_controller.'@resolveThread');



//Notes Routes
Route::post('/whatsapp/add-note', $whatsapp_controller.'@addNote');
Route::get('/whatsapp/fetch-notes', $whatsapp_note_controller.'@fetchNotes');


//Groups Routes
Route::post('/whatsapp/new-group', $whatsapp_group_controller.'@newGroup');
Route::get('/whatsapp/fetch-groups', $whatsapp_group_controller.'@fetchGroups');
Route::get('/whatsapp/fetch-group', $whatsapp_group_controller.'@fetchGroup');
Route::get('/whatsapp/group-chats', $whatsapp_group_controller.'@fetchGroupChats');
Route::get('/whatsapp/group-participants', $whatsapp_group_controller.'@fetchGroupParticipants');
Route::get('/whatsapp/group-participants/list', $whatsapp_group_controller.'@listGroupParticipants');
Route::post('/whatsapp/new-group-participant', $whatsapp_group_controller.'@newGroupParticipant');


//Canned Responses Routes
Route::get('whatsapp/fetch-canned-responses', $whatsapp_controller.'@cannedResponses');
Route::get('whatsapp/fetch-canned-response', $whatsapp_controller.'@cannedResponse');

//chat tags
Route::get('/whatsapp/chat-tags',$whatsapp_controller.'@fetchChatTags');
Route::get('/whatsapp/update-chat-tags',$whatsapp_controller.'@updateChatTags');


//Responses
Route::get('/responses/location', $whatsapp_controller.'@getLocation');
Route::get('/responses/business-hours', $whatsapp_controller.'@getBusinessHours');
Route::get('/responses/chat-with-an-agent', $whatsapp_controller.'@getChatWithAnAgent');
Route::get('/responses/sales-request', $whatsapp_controller.'@getSalesRequest');
Route::get('/responses/bill-enquiry', $whatsapp_controller.'@getBillEnquiry');
Route::get('/responses/bill-payment', $whatsapp_controller.'@getBillPayment');
Route::get('/responses/branch-visit', $whatsapp_controller.'@getBranchVisit');
Route::get('/responses/check', $whatsapp_controller.'@getCheck');
Route::get('/responses/new', $whatsapp_controller.'@getNew');
