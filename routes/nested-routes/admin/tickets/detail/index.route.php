<?php

use App\Http\Controllers\Admin\Tickets\Details\TicketDetailsController;
use App\Http\Controllers\Admin\Tickets\Details\TicketFilesController;
use Illuminate\Support\Facades\Route;

$controller = TicketDetailsController::class;
Route::get('/view/{id}', [$controller, 'index'])->name('ticket_view');
Route::post('/save_ticket_update', [$controller, 'storeTicketUpdate']);

Route::post('/update', [$controller, 'storeTicketUpdate']);
Route::get('/update/{id}', [$controller, 'updateTicket']);

Route::get('/update/edit/{id}', [$controller, 'editTicketUpdate']);
Route::post('/update/{id}', [$controller, 'saveUpdate']);

Route::get('/edit/{id}', [$controller, 'editTicket']);

Route::get('/align/{id}', [$controller, 'alignTicket']);
Route::post('/align/{id}', [$controller, 'alignTicketUpdate']);

Route::get('/update-history/{id}', [$controller, 'updateHistory']);

Route::get('/view_logs/{id}', [$controller, 'ticketUpdateLogs']);

Route::get('/ticket_attachments/{id}', [$controller, 'returnTicketAttachmentsView']);

Route::get('/facebook_messages/{id}', [$controller, 'facebookMessagesView']);
Route::get('/facebook_messages/inthread/{id}', [$controller, 'inThreadFacebookMessagesView']);
Route::get('/facebook_messages/all/{id}', [$controller, 'allFacebookMessagesView']);

Route::get('/retry/{mail_id}', [$controller, 'viewRetryTicketMailForm']);
Route::post('retry/{mail_id}', [$controller, 'retrySendingMail']);

Route::get('/view-ticket-update/{ticket}', [$controller, 'viewTicketUpdateDetails']);
Route::get('/load-reply-view/{ticketUpdateId}/{scannedMailId}', [$controller, 'returnReplyTicketView'])->name('load-reply-view');
Route::get('/load-reply-window/{ticketUpdateId}/{scannedMailId}', [$controller, 'returnReplyTicketWindow'])->name('load-reply-window');
Route::get('/load-forward-view/{scannedMailId}', [$controller, 'returnForwardTicketView']);
Route::get('/related/{id}/list', [$controller, 'listRelatedTickets']);
Route::post('/reply-mail/{ticket}', [$controller, 'replyToMailTicket']);
Route::post('/forward-mail/{id}', [$controller, 'forwardToMailTicket']);
Route::post('merge-selected-ticket/{id1}/{id2}', [$controller, 'mergeTickets']);
Route::post('unmerge-ticket/{id}', [$controller, 'unMergeTicket']);
Route::delete('/delete/{ticket}', [$controller, 'destroyTicket']);
Route::post('mark-spam/{id}', [$controller, 'markMailTicketAsSpam']);
Route::get('generatepdf/{id}', [$controller, 'generatePDF']);


Route::get('/escalations/{id}', [$controller, 'ticketEscalations']);
Route::get('/list/escalations/{id}', [$controller, 'listTicketEscalations']);
Route::post('/split_ticket_update', [$controller, 'splitTicketUpdate']);
Route::get('/ticket_note/{id}', [$controller, 'ticketNoteView']);
Route::get('/whatsapp-view/{id}', [$controller, 'returnWhatsappView']);

Route::get('/merge-ticket/{id}', [$controller, 'returnMergeTicketView']);
Route::get('/customer_history/{customer_id}', [$controller, 'returnCustomerHistoryView']);
Route::get('/view-mail/{mail_id}/{isReply?}', [$controller, 'viewTicketMail']);

Route::post('/mark-as-read/{ticket_update_id}', [$controller, 'markMailAsRead']);
Route::get('generatepdf/{id}', [$controller, 'generatePDF']);

Route::get('/ticket_watchers/{id}', [$controller, 'returnTicketWatchersView']);
Route::post('/storewatchers/{id}', [$controller, 'storeTicketWatchers']);


$controller1 = TicketFilesController::class;
Route::get('files/list/{id}', [$controller1, 'listTicketFiles']);
Route::get('file/download/{id}', [$controller1, 'downloadFile']);