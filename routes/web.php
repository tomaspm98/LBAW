<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CardController;
use App\Http\Controllers\ItemController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;

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

// AUTHENTICATION
Route::controller(LoginController::class)->group(function () {

    // Provide login form. Access: PUB
    Route::get('/login', 'showLoginForm')->name('login');

    // Login form submission. Access: PUB
    Route::post('/login', 'authenticate');

    // Logout the authenticated user. Access: MEM, ADM, MOD
    Route::post('/logout', 'logout')->name('logout');
});


// REGISTRATION
Route::controller(RegisterController::class)->group(function () {
    // Provide new user registration form. Access: PUB
    Route::get('/register','showRegistrationForm')->name('register');
    // Processes the new user registration form submission. Access: PUB
    Route::post('/register', 'register');
});


Route::get('questions/create', [QuestionController::class, 'createShow'])->name('questions.create');
Route::post('questions', [QuestionController::class, 'create'])->name('questions.create');
Route::get('questions/{question_id}', [QuestionController::class, 'show'])->name('questions.show');
Route::get('questions/{question_id}/edit', [QuestionController::class, 'editShow'])->name('questions.edit');
Route::put('questions/{question_id}', [QuestionController::class, 'update'])->name('questions.update');
Route::delete('questions/{question_id}', [QuestionController::class, 'delete'])->name('questions.delete');
Route::get('questions', [QuestionController::class, 'list'])->name('questions.list');
Route::post('questions/{question_id}/answers', [AnswerController::class, 'createAnswer'])->name('answers.create');
Route::get('questions/{question_id}/answers/{answer_id}/edit', [AnswerController::class, 'editShow'])->name('answers.edit');
Route::put('questions/{question_id}/answers/{answer_id}', [AnswerController::class, 'update'])->name('answers.update');
Route::delete('questions/{question_id}/answers/{answer_id}', [AnswerController::class, 'delete'])->name('answers.delete');







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


