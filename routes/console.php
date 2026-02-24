<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('users:verify-old {--dry}', function () {
    $dry = (bool) $this->option('dry');
    $count = User::whereNull('email_verified_at')->count();
    if ($dry) {
        $this->info("Found {$count} unverified users. Dry run: no changes made.");

        return;
    }
    $updated = User::whereNull('email_verified_at')->update(['email_verified_at' => now()]);
    $this->info("Marked {$updated} users as email verified.");
})->purpose('Mark currently unverified existing users as verified');
