<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use N8nAutomation\Http\Controllers\Api\AdScriptController;

Route::group(['prefix' => 'v1'], function () {
    Route::post('/ad-scripts', [AdScriptController::class, 'store'])->name('ad-scripts.store');
    Route::post('/ad-scripts/{id}/results', [AdScriptController::class, 'storeResults'])->name('ad-scripts.results');
});

