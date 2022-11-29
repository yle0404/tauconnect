<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login']);
Route::get('/posts', [CommentController::class, 'getPosts']);
Route::get('/announcements', [AnnouncementController::class, 'getAnnouncements']);
Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/write-post', [PostController::class, 'writePost']);
    Route::post('/post-comment', [CommentController::class, 'postComment']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/my-profile', [AuthController::class, 'getProfile']);
    Route::get('/my-complaints', [ComplaintsController::class, 'getMyComplaints']);
    Route::post('/submit-complaint', [ComplaintsController::class, 'submitComplaint']);
    Route::post('/conversation', [MessageController::class, 'getConversation']);
    Route::get('/users-to-message', [MessageController::class, 'getUsers']);
    Route::post('/send-message', [MessageController::class, 'sendMessage']);
    Route::post('/send-new-message', [MessageController::class, 'sendNewMessage']);
    Route::post('/update-profile-picture', [AuthController::class, 'updateProfilePicture']);
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::post('/post-comments', [CommentController::class, 'getPostComments']);
    Route::post('/post-announcement', [AnnouncementController::class, 'postAnnouncement']);
    Route::get('/notifications', [NotificationController::class, 'getNotifications']);
    Route::get('/new-users-to-message', [MessageController::class, 'getUsersToMessage']);
});