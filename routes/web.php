<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ResPostController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\LibDocumentController;
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
Route::get('email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::get('email/resend/{id}', [VerificationController::class, 'resend'])->name('verification.resend');


//** Routes for categories */

Route::resource('/category', CategoryController::class);

//** Routes for posts */

Route::resource('/post', PostController::class);

Route::get('post/category/{id}', [PostController::class, 'getPostsByCategory']);
Route::get('post/user/complete/{id}', [PostController::class, 'getCompletePostsByUser']);
Route::get('post/user/pending/{id}', [PostController::class, 'getPendingPostsByUser']);
Route::get('post/getrandom/posts', [PostController::class, 'getRandomPosts']);
Route::get('post/user/get/pending', [PostController::class, 'getPendingPost']);
Route::get('post/user/get/complete', [PostController::class, 'getCompletePost']);
Route::get('post/search/posts', [PostController::class, 'getPostsBySearch']);
Route::get('post/count/complete/{id}', [PostController::class, 'getCountCompletePosts']);
Route::get('post/count/pending/{id}', [PostController::class, 'getCountIncompletePosts']);
Route::get('post/count/all/pending', [PostController::class, 'getAllImcompletePosts']);


//** Routes for respost */
Route::resource('respost', ResPostController::class);
Route::get('respost/getrespostbypost/{id}', [ResPostController::class, 'getResPostByPost']);
Route::get('respost/getPostByAdminResPost/{id}', [ResPostController::class, 'getPostByAdminResPost']);
Route::post('respost/upload/', [ResPostController::class, 'upload']);
Route::put('respost/update/', [UserController::class, 'update']);
Route::get('respost/file/{filename}', [ResPostController::class, 'getFile']);
Route::get('respost/getrespost/count/{id}', [ResPostController::class, 'getCountPostsByAdminRepost']);


//** Routes for Document Type */

Route::resource('document-type', DocumentTypeController::class);

//** Router for Rol */

Route::resource('rol', RoleController::class);

//** Router for LibDocuemnt */

Route::resource('libdocument', LibDocumentController::class);
Route::get('libdocument/get/libdocumentbyuser/{id}',[LibDocumentController::class,'getLibDocumentsByUser']);
Route::post('libdocument/upload/',[LibDocumentController::class,'upload']);
Route::get('libdocument/file/{filename}', [LibDocumentController::class, 'getFile']);


