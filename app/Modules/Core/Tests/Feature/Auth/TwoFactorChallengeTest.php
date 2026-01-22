<?php

use App\Modules\Core\Models\User;
use Laravel\Fortify\Features;

it('redirects to login when not authenticated', function () {
    if (! Features::enabled(Features::twoFactorAuthentication())) {
        $this->markTestSkipped('Two factor authentication is not enabled.');
    }

    $response = $this->get(route('two-factor.login'));

    $response->assertRedirect(route('login'));
});

it('can render two factor challenge screen', function () {
    if (! Features::enabled(Features::twoFactorAuthentication())) {
        $this->markTestSkipped('Two factor authentication is not enabled.');
    }

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $user = User::factory()->create();

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('two-factor.login'));
});
