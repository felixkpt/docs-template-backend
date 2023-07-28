<?php
$controller = "WidgetController@";

Route::get('/',$controller.'iframe');
Route::get('iframe',$controller.'index');
Route::get('messages', $controller.'fetchMessages');
Route::post('messages', $controller.'sendMessage');
Route::post('invite', $controller.'sendInvite');
Route::get('contact-list', $controller.'getConversationList');
Route::get('users-list', $controller.'getUsers');
Route::post('mark-as-read', $controller.'markAsRead');
Route::get('unread-messages', $controller.'countUnreadMessages');
Route::get('/generate-user-ticket',$controller.'generateTicket');
