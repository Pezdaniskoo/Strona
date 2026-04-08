<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment('Done.');
})->purpose('Display an inspiring quote');

Schedule::command('todo:send-reminders')->dailyAt('08:00');
