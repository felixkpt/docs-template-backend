<?php

use App\Http\Controllers\Admin\Socialmedia\Twitterv1\Twitterv1Controller;

$twitter_controller = Twitterv1Controller::class;

Route::get('/',[$twitter_controller, 'index']);
Route::get('/chat-contacts', [$twitter_controller, 'getChatContacts']);
Route::get('/mention-contacts', [$twitter_controller, 'getMentionContacts']);
Route::get('/tweet-timelines', [$twitter_controller, 'getTweetTimelines']);
Route::get('/chat-history/{id}', [$twitter_controller, 'getChatHistory']);
Route::get('/mention-details/{id}', [$twitter_controller, 'mentionDetails']);
Route::get('/tweets/{date}', [$twitter_controller, 'loadTweets']);
Route::get('/history/{id}', [$twitter_controller, 'getChatHistory2']);
Route::get('/load-notes/{id}', [$twitter_controller, 'loadTwitterNotes']);
Route::get('/set-chat-as-read/{id}', [$twitter_controller, 'setChatAsRead']);
Route::get('/new-chats/{id}', [$twitter_controller, 'getNewChats']);
Route::get('/fetch-message/{contact_id}/{message_id}', [$twitter_controller, 'getNewMessage']);
Route::get('/chat-reply', [$twitter_controller, 'chatReply']);
Route::post('/chat-reply', [$twitter_controller, 'chatReply']);
Route::get('/contact/{id}', [$twitter_controller, 'getContact']);
Route::get('/all-contacts', [$twitter_controller, 'getAllContacts']);
Route::post('/upload-attachment', [$twitter_controller, 'uploadAttachment']);
Route::get('/my-chats-contacts', [$twitter_controller, 'myChatsContacts']);
Route::get('/confirm-is-agent', [$twitter_controller, 'confirmIsAgent']);
Route::post('notes', [$twitter_controller, 'saveTwitterNotes']);
Route::post('tmp', [$twitter_controller, 'saveTmpFiles']);
Route::post('tmp/delete', [$twitter_controller, 'deleteTmpFiles']);
//Canned Responses Routes
Route::get('/fetch-canned-responses', [$twitter_controller, 'cannedResponses']);
Route::get('/fetch-canned-response', [$twitter_controller, 'cannedResponse']);


Route::get('setpage/{id}', [$twitter_controller,'setTwitterPage']);

Route::get('/clearchat/{contactId}',[$twitter_controller,'clearChats']);
Route::get('/clearmentions/{contactId}',[$twitter_controller,'clearMentions']);
Route::get('/cleartweets/{date}',[$twitter_controller,'clearTweets']);
Route::get('/tabcounts',[$twitter_controller,'getTabCounts']);
Route::get('/pagestrip',[$twitter_controller,'renderPageStrip']);
Route::get('/getreplies/{tweetId}',[$twitter_controller,'getTweetReplies']);
Route::get('/tweet/comment/replies/{mentionId}',[$twitter_controller,'getTweetCommentReplies']);
Route::post('/mention-response',[$twitter_controller,'sendTwitterReply']);
Route::get('/create-ticket',[$twitter_controller,'convertToTicket']);
