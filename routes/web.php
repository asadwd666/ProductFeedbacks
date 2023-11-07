<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Models\Feedback;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [HomeController::class,'dashboard']);
Route::get('/{login?}/{register?}', [LoginController::class, 'index'])->name('login');
Route::post('/register-user', [LoginController::class, 'registerUser'])->name('register-user');
Route::post('/login-user', [LoginController::class, 'postLogin'])->name('login-user');
Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('/admin/dashboard', [AdminController::class,'index'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class,'listUser'])->name('admin.get-users');
    Route::post('/admin/posting', [AdminController::class,'postingPermission'])->name('admin.user-posting');
    // Route::post('/admin/blocked-users', [AdminController::class,'deleteUser'])->name('admin.blocked-users');
    Route::post('/admin/commenting', [AdminController::class,'commentsPermission'])->name('admin.user-commenting');


    Route::post('/admin/users/delete', [AdminController::class,'deleteUser'])->name('admin.user-delete');
    Route::post('/admin/users/{user}/suspend', [AdminController::class,'suspendUser'])->name('admin.users.suspend');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::post('add-feedbacks', [HomeController::class, 'saveFeedbacks'])->name('add-feedbacks');
    Route::post('post-comment', [HomeController::class, 'saveComments'])->name('post-comment');
    Route::post('add-vote', [HomeController::class, 'addVote'])->name('add-vote');

    Route::post('user-suggestions', [HomeController::class, 'mentionUser'])->name('user-suggestions');

    Route::post('logout', [LoginController::class, 'logout']);
});
