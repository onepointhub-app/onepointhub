<?php

use App\Modules\Core\Actions\Fortify\ResetUserPassword;
use App\Modules\Core\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

it('can reset user password with valid data', function () {
    $user = User::factory()->create();
    $oldPassword = $user->password;

    $action = new ResetUserPassword;

    $action->reset($user, [
        'password' => 'NewPassword123!',
        'password_confirmation' => 'NewPassword123!',
    ]);

    $user->refresh();

    expect($user->password)->not->toBe($oldPassword)
        ->and(Hash::check('NewPassword123!', $user->password))->toBeTrue();
});

it('throws validation exception with weak password', function () {
    $user = User::factory()->create();
    $action = new ResetUserPassword;

    expect(fn () => $action->reset($user, [
        'password' => '123',
        'password_confirmation' => '123',
    ]))->toThrow(ValidationException::class);
});

it('throws validation exception when passwords do not match', function () {
    $user = User::factory()->create();
    $action = new ResetUserPassword;

    expect(fn () => $action->reset($user, [
        'password' => 'NewPassword123!',
        'password_confirmation' => 'DifferentPassword123!',
    ]))->toThrow(ValidationException::class);
});

it('throws validation exception with missing password', function () {
    $user = User::factory()->create();
    $action = new ResetUserPassword;

    expect(fn () => $action->reset($user, []))->toThrow(ValidationException::class);
});
