<?php

use App\Modules\Core\Models\User;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Laravel\Fortify\Features;
use Livewire\Livewire;

beforeEach(function () {
    if (! Features::canManageTwoFactorAuthentication()) {
        $this->markTestSkipped('Two-factor authentication is not enabled.');
    }
});

it('can render recovery codes component', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('core::profile.two-factor.recovery-codes')
        ->assertSuccessful();
});

it('loads recovery codes when user has two factor enabled', function () {
    $user = User::factory()->create();

    $recoveryCodes = ['code1', 'code2', 'code3'];
    $user->forceFill([
        'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
    ])->save();

    $this->actingAs($user);

    $component = Livewire::test('core::profile.two-factor.recovery-codes');

    expect($component->get('recoveryCodes'))->toBe($recoveryCodes);
});

it('returns empty array when user does not have two factor enabled', function () {
    $user = User::factory()->withoutTwoFactor()->create();

    $this->actingAs($user);

    $component = Livewire::test('core::profile.two-factor.recovery-codes');

    expect($component->get('recoveryCodes'))->toBe([]);
});

it('returns empty array when recovery codes are not set', function () {
    $user = User::factory()->create();

    $user->forceFill([
        'two_factor_recovery_codes' => null,
    ])->save();

    $this->actingAs($user);

    $component = Livewire::test('core::profile.two-factor.recovery-codes');

    expect($component->get('recoveryCodes'))->toBe([]);
});

it('can regenerate recovery codes', function () {
    $user = User::factory()->create();

    $oldCodes = ['old-code-1', 'old-code-2'];
    $user->forceFill([
        'two_factor_recovery_codes' => encrypt(json_encode($oldCodes)),
    ])->save();

    $this->actingAs($user);

    $component = Livewire::test('core::profile.two-factor.recovery-codes')
        ->assertSet('recoveryCodes', $oldCodes)
        ->call('regenerateRecoveryCodes', app(GenerateNewRecoveryCodes::class));

    $newCodes = $component->get('recoveryCodes');

    expect($newCodes)->not->toBe($oldCodes)
        ->and($newCodes)->not->toBeEmpty()
        ->and(count($newCodes))->toBeGreaterThan(0);
});

it('handles decryption errors gracefully', function () {
    $user = User::factory()->create();

    // Set invalid encrypted data
    $user->forceFill([
        'two_factor_recovery_codes' => 'invalid-encrypted-data',
    ])->save();

    $this->actingAs($user);

    $component = Livewire::test('core::profile.two-factor.recovery-codes');

    $component->assertHasErrors('recoveryCodes')
        ->assertSet('recoveryCodes', []);
});

it('loads recovery codes on mount', function () {
    $user = User::factory()->create();

    $recoveryCodes = ['test-code-1', 'test-code-2'];
    $user->forceFill([
        'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
    ])->save();

    $this->actingAs($user);

    $component = Livewire::test('core::profile.two-factor.recovery-codes');

    expect($component->get('recoveryCodes'))->toBe($recoveryCodes);
});
