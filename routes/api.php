<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IotDeviceController;

Route::post('/iot-devices', [IotDeviceController::class, 'index']);

