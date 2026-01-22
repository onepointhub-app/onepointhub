<?php

use App\Modules\Core\Actions\Fortify\CreateNewUser;
use App\Modules\Core\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

it('can create a new user with valid data', function () {
    $action = new CreateNewUser;

    $user = $action->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->name)->toBe('John Doe')
        ->and($user->email)->toBe('john@example.com')
        ->and($user->exists)->toBeTrue()
        ->and(Hash::check('Password123!', $user->password))->toBeTrue();
});

it('throws validation exception with invalid email', function () {
    $action = new CreateNewUser;

    expect(fn () => $action->create([
        'name' => 'John Doe',
        'email' => 'invalid-email',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]))->toThrow(ValidationException::class);
});

it('throws validation exception with duplicate email', function () {
    User::factory()->create(['email' => 'existing@example.com']);

    $action = new CreateNewUser;

    expect(fn () => $action->create([
        'name' => 'John Doe',
        'email' => 'existing@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]))->toThrow(ValidationException::class);
});

it('throws validation exception with missing name', function () {
    $action = new CreateNewUser;

    expect(fn () => $action->create([
        'email' => 'john@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]))->toThrow(ValidationException::class);
});

it('throws validation exception with weak password', function () {
    $action = new CreateNewUser;

    expect(fn () => $action->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => '123',
        'password_confirmation' => '123',
    ]))->toThrow(ValidationException::class);
});

it('throws validation exception when passwords do not match', function () {
    $action = new CreateNewUser;

    expect(fn () => $action->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'DifferentPassword123!',
    ]))->toThrow(ValidationException::class);
});
