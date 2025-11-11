<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use N8nAutomation\Http\Controllers\Api\AdScriptController;

Route::group(['prefix' => 'v1'], function () {
    Route::post('/ad-scripts', [AdScriptController::class, 'store'])->name('ad-scripts.store');
});

