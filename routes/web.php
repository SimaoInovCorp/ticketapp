<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Tickets
    Route::get('/tickets', [App\Http\Controllers\TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [App\Http\Controllers\TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [App\Http\Controllers\TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [App\Http\Controllers\TicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{ticket}/edit', [App\Http\Controllers\TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('/tickets/{ticket}', [App\Http\Controllers\TicketController::class, 'update'])->name('tickets.update');
    Route::delete('/tickets/{ticket}', [App\Http\Controllers\TicketController::class, 'destroy'])->name('tickets.destroy');

    // Entities (operators only — enforced via policy in controller)
    Route::get('/entities', [App\Http\Controllers\EntityController::class, 'index'])->name('entities.index');
    Route::get('/entities/create', [App\Http\Controllers\EntityController::class, 'create'])->name('entities.create');
    Route::post('/entities', [App\Http\Controllers\EntityController::class, 'store'])->name('entities.store');
    Route::get('/entities/{entity}', [App\Http\Controllers\EntityController::class, 'show'])->name('entities.show');
    Route::get('/entities/{entity}/edit', [App\Http\Controllers\EntityController::class, 'edit'])->name('entities.edit');
    Route::put('/entities/{entity}', [App\Http\Controllers\EntityController::class, 'update'])->name('entities.update');
    Route::delete('/entities/{entity}', [App\Http\Controllers\EntityController::class, 'destroy'])->name('entities.destroy');

    // Contacts (operators only — enforced via policy in controller)
    Route::get('/contacts', [App\Http\Controllers\ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/create', [App\Http\Controllers\ContactController::class, 'create'])->name('contacts.create');
    Route::post('/contacts', [App\Http\Controllers\ContactController::class, 'store'])->name('contacts.store');
    Route::get('/contacts/{contact}', [App\Http\Controllers\ContactController::class, 'show'])->name('contacts.show');
    Route::get('/contacts/{contact}/edit', [App\Http\Controllers\ContactController::class, 'edit'])->name('contacts.edit');
    Route::put('/contacts/{contact}', [App\Http\Controllers\ContactController::class, 'update'])->name('contacts.update');
    Route::delete('/contacts/{contact}', [App\Http\Controllers\ContactController::class, 'destroy'])->name('contacts.destroy');

    // Admin
    Route::get('/admin/inboxes', [App\Http\Controllers\Admin\InboxController::class, 'index'])->name('admin.inboxes.index');

    // About
    Route::inertia('/about', 'About')->name('about');
});

require __DIR__.'/settings.php';
