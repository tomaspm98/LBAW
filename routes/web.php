<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CardController;
use App\Http\Controllers\ItemController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AdminController;

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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'] )->name('home') ;
// Route::redirect('/login', '/login');

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

// STATIC PAGES
Route::get('/about', function () {
    return view('pages.about');
})->name('about');



Route::controller(QuestionController::class)->group(function(){

    Route::get('questions/create', 'createShow')->name('questions.create');
    Route::post('questions', 'create')->name('questions.create');
    Route::get('questions/{question_id}', 'show')->name('questions.show');
    Route::get('questions/{question_id}/edit', 'editShow')->name('questions.edit');
    Route::put('questions/{question_id}', 'update')->name('questions.update');
    Route::delete('questions/{question_id}', 'delete')->name('questions.delete');
    Route::get('questions', 'list')->name('questions.list');

});

Route::controller(AnswerController::class)->group(function(){
    Route::post('questions/{question_id}/answers', 'createAnswer')->name('answers.create');
    Route::get('questions/{question_id}/answers/{answer_id}/edit', 'editShow')->name('answers.edit');
    Route::put('questions/{question_id}/answers/{answer_id}', 'update')->name('answers.update');
    Route::delete('questions/{question_id}/answers/{answer_id}', 'delete')->name('answers.delete');
});



Route::controller(AdminController::class)->group(function(){

    Route::get('/admin/assign', 'showAllUsers')->name('admin.users');
    Route::get('/admin/remove', 'showAllModerators')->name('admin.moderators');
    Route::get('add/{userId}', 'addModerator')->name('moderator.add');
    Route::delete('remove/{userId}', 'removeModerator')->name('moderator.remove');
});

Route::controller(UserController::class)->group(function(){
    Route::get('member/{user_id}', 'show')->name('member.show');
    Route::get('member/{user_id}/edit', 'editShow')->name('member.edit');
    Route::put('member/{user_id}', 'update')->name('user.update');
    Route::delete('member/{user_id}/delete', 'delete')->name('user.delete');
});

// SEARCH PAGE
Route::get('/search', [App\Http\Controllers\SearchController::class, 'search'])->name('search');
