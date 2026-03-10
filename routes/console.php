<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Platform update check - runs daily at 2 AM
Schedule::command('platform:check-updates')->dailyAt('02:00');

// Also run a check on Sundays for weekly maintenance
Schedule::command('platform:check-updates --force')->weeklyOn(0, '03:00');
