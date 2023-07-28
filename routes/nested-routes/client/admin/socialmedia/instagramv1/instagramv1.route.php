<?php

use App\Http\Controllers\Admin\Socialmedia\Instagramv1\InstagramController;
use Illuminate\Support\Facades\Route;

$controller = InstagramController::class;


Route::get('/',[$controller, 'index']);
Route::get('/chat-contacts', [$controller, 'getChatContacts']);
Route::get('/mention-contacts', [$controller, 'getMentionContacts']);
Route::get('/chat-history/{id}', [$controller, 'getChatHistory']);
Route::get('/mention-details/{id}', [$controller, 'mentionDetails']);
Route::get('/history/{id}', [$controller, 'getChatHistory2']);
Route::get('/load-notes/{id}', [$controller, 'loadInstagramNotes']);
Route::get('/set-chat-as-read/{id}', [$controller, 'setChatAsRead']);
Route::get('/new-chats/{id}', [$controller, 'getNewChats']);
Route::get('/fetch-message/{contact_id}/{message_id}', [$controller, 'getNewMessage']);
Route::get('/chat-reply', [$controller, 'chatReply']);
Route::post('/chat-reply', [$controller, 'chatReply']);
Route::get('/contact/{id}', [$controller, 'getContact']);
Route::get('/all-contacts', [$controller, 'getAllContacts']);
Route::post('/upload-attachment', [$controller, 'uploadAttachment']);
Route::get('/my-chats-contacts', [$controller, 'myChatsContacts']);
Route::get('/confirm-is-agent', [$controller, 'confirmIsAgent']);
Route::post('notes', [$controller, 'saveInstagramNotes']);
Route::post('tmp', [$controller, 'saveTmpFiles']);
Route::post('tmp/delete', [$controller, 'deleteTmpFiles']);
//Canned Responses Routes
Route::get('/fetch-canned-responses', [$controller, 'cannedResponses']);
Route::get('/fetch-canned-response', [$controller, 'cannedResponse']);


Route::get('setpage/{id}', [$controller,'setInstagramPage']);

Route::get('/clearchat/{contactId}',[$controller,'clearChats']);
Route::get('/clearmentions/{contactId}',[$controller,'clearMentions']);
Route::get('/cleartweets/{date}',[$controller,'clearTweets']);
Route::get('/tabcounts',[$controller,'getTabCounts']);
Route::get('/pagestrip',[$controller,'renderPageStrip']);
Route::get('/getreplies/{tweetId}',[$controller,'getTweetReplies']);
Route::get('/tweet/comment/replies/{mentionId}',[$controller,'getTweetCommentReplies']);
Route::post('/mention-response',[$controller,'sendInstagramReply']);
Route::get('/create-ticket',[$controller,'convertToTicket']);
