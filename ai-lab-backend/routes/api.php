<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermitController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ResearchController;
use App\Http\Controllers\NewsController;

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

//Member
Route::get('/members', [MemberController::class, 'index']);
Route::get('/members/{id}', [MemberController::class, 'show']);

// Kalau admin ingin input dari backend admin
Route::post('/members/create', [MemberController::class, 'store']);

//Research
Route::get('/research/products', [ResearchController::class, 'products']);
Route::get('/research/partners', [ResearchController::class, 'partners']);

//News
Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/latest', [NewsController::class, 'latest']);
Route::get('/news/{id}', [NewsController::class, 'show']);

// Optional protected route for admin create
Route::post('/news', [NewsController::class, 'store']); // protect with auth/sanctum in production

