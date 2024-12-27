<?php

use App\Http\Controllers\ControlController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\Users;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('registrasi', [ProfileController::class, 'create']);
Route::post('login', [ProfileController::class, 'login']);

Route::prefix('device')->middleware(Users::class)->group(function(){
    Route::post('create', [DeviceController::class, 'create']);
    Route::get('list', [DeviceController::class, 'list']);
    Route::put('edit', [DeviceController::class, 'edit']);
    Route::delete('delete', [DeviceController::class, 'delete']);
});

Route::prefix('data')->middleware(Users::class)->group(function(){
    Route::post('create', [DataController::class, 'create']);
    Route::get('list', [DataController::class, 'list']);
});

Route::prefix('control')->middleware(Users::class)->group(function(){
    Route::post('create', [ControlController::class, 'create']);
    Route::get('list', [ControlController::class, 'list']);
});
