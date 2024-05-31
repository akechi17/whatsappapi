<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/callback/paymentG/linkqu', [PaymentController::class, 'handleLinkQuCallback']);