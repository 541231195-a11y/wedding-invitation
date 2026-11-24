<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\Api\GuestBookController;

Route::get('/', [InvitationController::class, 'showDefault']);

Route::get('/{slug}', [InvitationController::class, 'show']);

Route::get('/countries', [GuestBookController::class, 'countries']);
Route::get('/guest-books/options/{invitation_id}', [GuestBookController::class, 'options']);
Route::get('/guest-books/{invitation_id}', [GuestBookController::class, 'index']);
Route::post('/guest-books/{invitation_id}', [GuestBookController::class, 'store']);
