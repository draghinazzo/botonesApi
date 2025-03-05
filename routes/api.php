<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EntregaController;
use App\Http\Controllers\ComponenteController;

Route::apiResource('entregas', EntregaController::class);

Route::apiResource('componentes', ComponenteController::class);