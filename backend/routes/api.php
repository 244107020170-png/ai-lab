<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermitController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ActivityController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Semua endpoint backend yang akan dipanggil oleh frontend masuk ke sini.
| Base URL-nya: http://127.0.0.1:8000/api/...
|--------------------------------------------------------------------------
*/

// -------------------------------------------------------------------
// Lab Use Permit API
// -------------------------------------------------------------------
Route::post('/permit/submit', [PermitController::class, 'submit']);
Route::middleware('auth:sanctum')->group(function(){
  Route::get('/permit/all', [PermitController::class, 'index']); // admin only: check role
  Route::post('/permit/{id}/approve', [PermitController::class, 'approve']);
  Route::post('/permit/{id}/reject', [PermitController::class, 'reject']);
});


// -------------------------------------------------------------------
// Volunteer Registration API
// -------------------------------------------------------------------
Route::post('/volunteer/submit', [VolunteerController::class, 'submit']);
Route::get('/volunteer/all', [VolunteerController::class, 'index']); // Admin melihat semua

//Route 
Route::post('/admin/login', [AuthController::class, 'login']);

//Activity
Route::get('/activities', [ActivityController::class, 'index']);
Route::get('/activities/{id}', [ActivityController::class, 'show']);
Route::post('/activities', [ActivityController::class, 'store']);