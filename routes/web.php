<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CardController;
use App\Http\Controllers\ItemController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home
Route::redirect('/', '/login');

Route::get('questions/{question_id}', [QuestionController::class, 'show'])->name('questions.show');
Route::get('questions/{question_id}/edit', [QuestionController::class, 'editShow'])->name('questions.edit');
Route::put('questions/{question_id}', [QuestionController::class, 'update'])->name('questions.update');

Route::get('member/{user_id}', [UserController::class, 'show'])->name('member.show');
Route::get('member/{user_id}/edit', [UserController::class, 'editShow'])->name('member.edit');
Route::put('member/{user_id}', [UserController::class, 'update'])->name('user.update');
Route::get('member/{user_id}/delete', [UserController::class, 'delete'])->name('user.delete');



// Cards
/*Route::controller(CardController::class)->group(function () {
    Route::get('/cards', 'list')->name('cards');
    Route::get('/cards/{id}', 'show');
});


// API
Route::controller(CardController::class)->group(function () {
    Route::put('/api/cards', 'create');
    Route::delete('/api/cards/{card_id}', 'delete');
});

Route::controller(ItemController::class)->group(function () {
    Route::put('/api/cards/{card_id}', 'create');
    Route::post('/api/item/{id}', 'update');
    Route::delete('/api/item/{id}', 'delete');
});


// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});*/


