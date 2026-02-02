<?php

use App\Http\Controllers\TicketAttachmentController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin')->name('home');

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/private/ticket-attachments/{ticketId}/{filename}',
        [TicketAttachmentController::class, 'show']
    )->name('attachment');
});
