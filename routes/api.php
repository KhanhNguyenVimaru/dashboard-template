<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IotDeviceController;

Route::get('/iot-devices', [IotDeviceController::class, 'index']);

