<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// Register console commands
// Note: Commands in app/Console/Commands are auto-discovered by Laravel
// No need to manually register them here

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
