<?php

use Illuminate\Support\Facades\Schedule;

// Ejecutar todos los domingos a las 2:00 AM
Schedule::command('evidences:purge')
    ->weekly()
    ->sundays()
    ->at('02:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/evidences-purge.log'));
