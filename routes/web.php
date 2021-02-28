<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PruebaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ResPostController;
use App\Http\Controllers\VerificationController;
use App\Http\Middleware\ApiAuthMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//** api-routes users
Route::post('user/register/', [UserController::class, 'register']);
Route::post('user/login/', [UserController::class, 'login']);
Route::put('user/update/', [UserController::class, 'update']);
Route::post('user/upload/', [UserController::class, 'upload'])->middleware(ApiAuthMiddleware::class);
Route::get('user/avatar/{filename}', [UserController::class, 'getImage']);
Route::get('user/detail/{id}', [UserController::class, 'detail']);
Route::get('email/verify/{id}',[VerificationController::class,'verify'])->name('verification.verify');
Route::get('email/resend/{id}',[VerificationController::class,'resend'])->name('verification.resend');



//** Routes for categories */

Route::resource('/category', CategoryController::class);

//** Routes for posts */

Route::resource('/post', PostController::class);

Route::get('post/category/{id}', [PostController::class, 'getPostsByCategory']);
Route::get('post/user/{id}', [PostController::class, 'getPostsByUser']);
Route::get('post/getrandom/posts', [PostController::class, 'getRandomPosts']);
Route::get('post/user/get/pending', [PostController::class, 'getPendingPost']);
Route::get('post/search/posts', [PostController::class, 'getPostsBySearch']);

//** Routes for respost */

Route::resource('respost', ResPostController::class);
Route::get('respost/getrespostbypost/{id}', [ResPostController::class, 'getResPostByPost']);
Route::get('respost/getPostByAdminResPost/{id}', [ResPostController::class, 'getPostByAdminResPost']);
Route::post('respost/upload/', [ResPostController::class, 'upload']);
Route::put('respost/update/', [UserController::class, 'update']);
Route::get('respost/file/{filename}', [ResPostController::class, 'getFile']);
