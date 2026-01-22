<?php

use App\Modules\Core\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

it('can render profile screen', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/profile')->assertOk();
});

it('can update profile information', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('core::profile.profile')
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    $user->refresh();

    expect($user->name)->toEqual('Test User')
        ->and($user->email)->toEqual('test@example.com')
        ->and($user->email_verified_at)->toBeNull();
});

it('does not change email verification status when email address is unchanged', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('core::profile.profile')
        ->set('name', 'Test User')
        ->set('email', $user->email)
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

it('can delete user account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('core::profile.delete-user-form')
        ->set('password', 'password')
        ->call('deleteUser');

    $response
        ->assertHasNoErrors()
        ->assertRedirect('/');

    expect($user->fresh())->toBeNull()
        ->and(auth()->check())->toBeFalse();
});

it('requires correct password to delete account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('core::profile.delete-user-form')
        ->set('password', 'wrong-password')
        ->call('deleteUser');

    $response->assertHasErrors(['password']);

    expect($user->fresh())->not->toBeNull();
});

it('can resend verification notification when email is unverified', function () {
    Notification::fake();

    $user = User::factory()->unverified()->create();

    $this->actingAs($user);

    $response = Livewire::test('core::profile.profile')
        ->call('resendVerificationNotification');

    $response->assertHasNoErrors();

    Notification::assertSentTo($user, VerifyEmail::class);
});

it('redirects when resending verification notification for already verified user', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('core::profile.profile')
        ->call('resendVerificationNotification');

    $response->assertRedirect(route('home'));
});
