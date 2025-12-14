<?php

use App\Http\Controllers\Api\Kestra\DailyCheckinDataController;

Route::prefix('kestra')
    ->middleware(['kestra.auth']) // custom auth, NOT user auth
    ->group(function () {

        Route::get('/daily-checkin-context/{user}', 
            [DailyCheckinDataController::class, 'context']
        );

        Route::post('/recovery/apply/{user}',
            [DailyCheckinDataController::class, 'applyRecovery']
        );
    });

Route::get('/kestra/ping', function () {
    return ['ok' => true, 'time' => now()->toDateTimeString()];
});

