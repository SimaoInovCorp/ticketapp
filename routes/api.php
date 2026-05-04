<?php

use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\EntityController;
use App\Http\Controllers\Api\InboxController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\TicketMessageController;
use App\Http\Controllers\Api\TicketStatusController;
use App\Http\Controllers\Api\TicketTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'verified'])->name('api.')->group(function () {
    // Inboxes
    Route::apiResource('inboxes', InboxController::class);
    Route::post('inboxes/{inbox}/operators', [InboxController::class, 'syncOperators'])->name('inboxes.operators.sync');

    // Entities & Contacts
    Route::apiResource('entities', EntityController::class);
    Route::apiResource('contacts', ContactController::class);

    // Tickets
    Route::apiResource('tickets', TicketController::class);
    Route::patch('tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
    Route::patch('tickets/{ticket}/status', [TicketController::class, 'changeStatus'])->name('tickets.status');
    Route::post('tickets/{ticket}/test-email', [TicketController::class, 'testEmail'])->name('tickets.test-email');

    // Ticket Messages
    Route::post('tickets/{ticket}/messages', [TicketMessageController::class, 'store'])->name('tickets.messages.store');

    // Lookup tables
    Route::apiResource('ticket-types', TicketTypeController::class)->except(['show']);
    Route::apiResource('ticket-statuses', TicketStatusController::class)->except(['show']);
});
