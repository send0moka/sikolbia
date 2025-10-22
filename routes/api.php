<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatbotController;
use App\Http\Controllers\Api\StructuredSearchController;

Route::post('/chatbot', [ChatbotController::class, 'handle']);
Route::post('/chatbot/reset', [ChatbotController::class, 'reset']);
Route::get('/structured/search', [StructuredSearchController::class, 'search']);