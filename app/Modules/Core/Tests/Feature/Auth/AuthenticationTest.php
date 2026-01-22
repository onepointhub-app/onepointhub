<?php

use App\Modules\Core\Models\User;
use Laravel\Fortify\Features;

it('can render login screen', function () {
    $response = $this->get(route('login'));

    $response->assertStatus(200);
});

it('can authenticate a user using the login screen', function () {
    $user = User::factory()->withoutTwoFactor()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('home', absolute: false));

    $this->assertAuthenticatedAs($user);
});

it('cannot authenticate a user with invalid password', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrorsIn('email');

    $this->assertGuest();
});

it('redirects users to two factor challenge if enabled', function () {
    if (! Features::canManageTwoFactorAuthentication()) {
        $this->markTestSkipped('Two factor authentication is not enabled.');
    }

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $user = User::factory()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('two-factor.login'));

    $this->assertGuest();
});

it('can logout users', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect('/');

    $this->assertGuest();
});

it('cannot login with non-existent email', function () {
    $response = $this->post(route('login.store'), [
        'email' => 'nonexistent@example.com',
        'password' => 'password',
    ]);

    $response->assertSessionHasErrorsIn('email');
    $this->assertGuest();
});

it('cannot login with empty email', function () {
    $response = $this->post(route('login.store'), [
        'email' => '',
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

it('cannot login with empty password', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => '',
    ]);

    $response->assertSessionHasErrors('password');
    $this->assertGuest();
});

it('respects remember me checkbox', function () {
    $user = User::factory()->withoutTwoFactor()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
        'remember' => true,
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertAuthenticatedAs($user);

    // Check that remember the token is set
    $user->refresh();
    expect($user->remember_token)->not->toBeNull();
});

it('does not set remember token when remember me is not checked', function () {
    $user = User::factory()->withoutTwoFactor()->create();
    $originalRememberToken = $user->remember_token;

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
        'remember' => false,
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertAuthenticatedAs($user);

    $user->refresh();
    expect($user->remember_token)->toBe($originalRememberToken);
});
