<?php

use App\Http\Controllers\Admin\Barbers\BarberControllelr;
use App\Http\Controllers\Admin\Bron\BronController;
use App\Http\Controllers\Admin\Notifactions\NotifactionController;
use App\Http\Controllers\Admin\Orders\OrderController;
use App\Http\Controllers\Admin\UserCommets\CommetController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Barber\Commmets\CommetController as CommmetsCommetController;
use App\Http\Controllers\Barber\DayOff\DayOffController;
use App\Http\Controllers\Barber\Profile\ProfileController;
use App\Http\Controllers\Barber\Service\ServiceController;
use App\Http\Controllers\Barber\WorkTime\WorkTimeController;
use App\Http\Controllers\CometController;
use App\Http\Controllers\Users\BarbersDate\DateController;
use App\Http\Controllers\Users\BarberServicePost\PostControlller;
use App\Http\Controllers\Users\BarbersGet\GetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Profiler\Profile;





// Auth start
Route::post('login', [AuthController::class, 'login']);
// Auth End



Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {


    // Admin Panel start

    // Barber crud start
    Route::prefix('barbers')->group(function () {
        Route::get('/', [BarberControllelr::class, 'index']);       // Barberlar ro'yxati
        Route::post('/', [BarberControllelr::class, 'store']);      // Yangi barber yaratish
        Route::get('{id}', [BarberControllelr::class, 'show']);     // ID orqali ko'rsatish
        Route::post('{id}', [BarberControllelr::class, 'update']); // PATCH bilan yangilash
        Route::delete('{id}', [BarberControllelr::class, 'destroy']); // O'chirish
    });
    // Barber crud end

    // Admn Bron start
    Route::post('bronAdmin', [BronController::class, 'post']);
    // Admin Bron end

    // Order Start
    Route::resource('orders', OrderController::class);
    // Order End

    // Users Commet Start
    Route::resource('commets', CommetController::class);
    // Users Commet End

    // Notifactions start
    Route::prefix('admin/notifications')->group(function () {
        Route::get('/', [NotifactionController::class, 'index']);
        Route::get('{id}', [NotifactionController::class, 'show']);
        Route::delete('{id}', [NotifactionController::class, 'destroy']);
    });

    // Notifactions End

    // Admin Panel end


});


Route::middleware(['auth:sanctum', 'role:barber'])->group(function () {

    // Barber Start

// profile start

    Route::get('profile', [ProfileController::class, 'show']);
    Route::post('profile', [ProfileController::class, 'update']);
// profile end

// service start
    Route::apiResource('services', ServiceController::class);
// service end

// Work Time Start
 Route::apiResource('worktime', WorkTimeController::class);
// Work Time End

// DayOFF start
Route::apiResource('dayoff', DayOffController::class);
// DayOFF End

// Notifaction start
 Route::prefix('barber/notifications')->group(function () {
        Route::get('/', [NotifactionController::class, 'index']);
        Route::get('{id}', [NotifactionController::class, 'show']);
        Route::delete('{id}', [NotifactionController::class, 'destroy']);
    });
// Notifaction End

// Commmet start
Route::apiResource('commets', CommmetsCommetController::class);
// Commmet End
    // Barber End

});



// Users Start

//  Barber Get
Route::get('barberss', [GetController::class, 'index']);
Route::get('barberss/{id}', [GetController::class, 'show']);

// Barber Serive get

Route::get('/service', [PostControlller::class, 'index']);
Route::get('/serviceId', [PostControlller::class, 'show']);


Route::get('/date/{user_id}', [DateController::class, 'getAvailableTimesForDate']);

    Route::post('bron', [BronController::class, 'post']);

Route::post('commet', [CometController::class, 'store']);
// Users End
