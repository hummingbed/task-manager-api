<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Public Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Require Sanctum Authentication)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Get authenticated user info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Logout route
    Route::post('/logout', function (Request $request) {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    });

    /*
    |--------------------------------------------------------------------------
    | Task Routes (CRUD)
    |--------------------------------------------------------------------------
    */
    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index']);          // GET /api/tasks → list all user tasks
        Route::post('/', [TaskController::class, 'store']);         // POST /api/tasks → create a task
        Route::get('/{id}', [TaskController::class, 'show']);       // GET /api/tasks/{id} → get a single task
        Route::put('/{id}', [TaskController::class, 'update']);     // PUT /api/tasks/{id} → update a task
        Route::delete('/{id}', [TaskController::class, 'destroy']); // DELETE /api/tasks/{id} → delete a task
    });
});