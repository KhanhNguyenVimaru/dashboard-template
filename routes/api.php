<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IotDeviceController;
use App\Http\Controllers\RouterController;

Route::get('/iot-devices', [IotDeviceController::class, 'index']);
Route::post('/add-iot-device', [IotDeviceController::class, 'store']);
Route::put('/iot-devices/{iotDevice}', [IotDeviceController::class, 'update']);
Route::delete('/iot-devices/{iotDevice}', [IotDeviceController::class, 'destroy']);

Route::get('/routers', [RouterController::class, 'index']);
Route::post('/add-router', [RouterController::class, 'store']);


