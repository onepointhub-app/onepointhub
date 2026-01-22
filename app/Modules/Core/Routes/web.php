<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', fn () => 'Hello World')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::livewire('/profile', 'core::profile.profile')->name('profile.edit');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('profile/password', 'core::profile.password')->name('profile.password.edit');

    Route::livewire('profile/two-factor', 'core::profile.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
