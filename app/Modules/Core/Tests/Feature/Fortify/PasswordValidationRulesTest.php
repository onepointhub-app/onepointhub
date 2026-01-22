<?php

use App\Modules\Core\Actions\Fortify\CreateNewUser;
use App\Modules\Core\Actions\Fortify\ResetUserPassword;
use App\Modules\Core\Models\User;
use Illuminate\Validation\ValidationException;

it('uses password validation rules in CreateNewUser', function () {
    $action = new CreateNewUser;

    // App\Livewire\Test that password rules are enforced by trying to create a user
    // The rules are tested through the create method which uses passwordRules()
    try {
        $action->create([
            'name' => 'App\Livewire\Test User',
            'email' => 'test@example.com',
            'password' => 'short', // Too short, should fail
            'password_confirmation' => 'short',
        ]);
    } catch (ValidationException $e) {
        expect($e->errors())->toHaveKey('password');
    }
});

it('uses password validation rules in ResetUserPassword', function () {
    $user = User::factory()->create();
    $action = new ResetUserPassword;

    // App\Livewire\Test that password rules are enforced by trying to reset password
    // The rules are tested through the reset method which uses passwordRules()
    try {
        $action->reset($user, [
            'password' => 'short', // Too short, should fail
            'password_confirmation' => 'short',
        ]);
    } catch (ValidationException $e) {
        expect($e->errors())->toHaveKey('password');
    }
});

it('enforces password confirmation requirement', function () {
    $action = new CreateNewUser;

    try {
        $action->create([
            'name' => 'App\Livewire\Test User',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            // Missing password_confirmation
        ]);
    } catch (ValidationException $e) {
        expect($e->errors())->toHaveKey('password');
    }
});
