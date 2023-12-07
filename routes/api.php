<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\PremiseController;
use App\Http\Controllers\ReservationController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// users


Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');

Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');

Route::post('/clients/login', [ClientController::class, 'login'])->name('clients.login');

Route::get('/clients/{id}', [ClientController::class, 'show'])->name('clients.show');

Route::match(['put', 'patch'], '/clients/{id}', [ClientController::class, 'update'])->name('clients.update');

Route::delete('/clients/{id}', [ClientController::class, 'destroy'])->name('clients.destroy');

// premises

Route::get('/premises', [PremiseController::class, 'index'])->name('premises.index');

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post("/premises/{id}/profile", [PremiseController::class, "profilePicture"]);

    Route::get('/premises/owner/{id}', [PremiseController::class, 'indexByClient'])->name('premises.owner');

    Route::get('/premises/owner/reservations/{id}', [PremiseController::class, 'indexByPremise'])->name('premises.owner');

    Route::post('/premises', [PremiseController::class, 'store'])->name('premises.store');

    Route::get('/premises/{id}', [PremiseController::class, 'show'])->name('premises.show');

    Route::match(['put', 'patch'], '/premises/{id}', [PremiseController::class, 'update'])->name('premises.update');

    Route::delete('/premises/{id}', [PremiseController::class, 'destroy'])->name('premises.destroy');

});

// reservations

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');

    Route::get('/reservations/owner/{id}', [ReservationController::class, 'indexByClient'])->name('reservations.owner');

    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');

    Route::get('/reservations/{id}', [ReservationController::class, 'show'])->name('reservations.show');

    Route::match(['put', 'patch'], '/reservations/{id}', [ReservationController::class, 'update'])->name('reservations.update');

    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

});



