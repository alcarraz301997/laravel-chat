<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConversationsController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\ParticipantsController;
use App\Http\Controllers\UsersController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::prefix('v1')->middleware('auth')->group(function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'login'])->withoutMiddleware(['auth']);
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('', [UsersController::class, 'list']);
        Route::post('', [UsersController::class, 'store'])->withoutMiddleware(['auth']);
        Route::post('img', [UsersController::class, 'updateImage']);
        Route::patch('', [UsersController::class, 'update']);
        Route::delete('', [UsersController::class, 'delete']);
    });

    Route::group(['prefix' => 'conversations'], function () {
        Route::get('', [ConversationsController::class, 'list']);
        Route::get('{id}', [ConversationsController::class, 'listConversation']);
        Route::post('', [ConversationsController::class, 'store']);
        Route::post('view/{id}', [ConversationsController::class, 'messageView']);
        Route::patch('{id}', [ConversationsController::class, 'updateName']);
        Route::delete('{id}', [ConversationsController::class, 'delete']);
    });

    Route::group(['prefix' => 'participants'], function () {
        // Route::get('', [ParticipantsController::class, 'list']);
        Route::post('', [ParticipantsController::class, 'store']);
        Route::delete('{id}', [ParticipantsController::class, 'delete']);
    });

    Route::group(['prefix' => 'messages'], function () {
        Route::get('{id}', [MessagesController::class, 'list']);
        Route::post('', [MessagesController::class, 'store']);
        Route::delete('{id}', [MessagesController::class, 'delete']);
    });
});

Route::fallback(function () {
    return [
        'timestamp' => Carbon::now()->toDateTimeString(),
        'code'      => 404,
        'status'    => false,
        'data'      => ['message' => 'Acceso restringido.']
    ];
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
