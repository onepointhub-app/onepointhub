<?php

use App\Modules\Core\Concerns\PasswordValidationRules;
use App\Modules\Core\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

new class extends Component {
    use PasswordValidationRules;

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            /** @var string[] $validated */
            $validated = $this->validate([
                'current_password' => $this->currentPasswordRules(),
                'password' => $this->passwordRules(),
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        /** @var User $user */
        $user = Auth::user();

        $user->update([
            'password' => ($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}
?>

<section class="w-full">
    <x-core::profile.layout :heading="__('Update password')"
                            :subheading="__('Ensure your account is using a long, random password to stay secure.')">
        @if (session('status') === 'password-updated')
            <x-alert icon="o-check-circle" class="alert-success mb-4">
                <p class="font-medium">
                    Password updated successfully.
                </p>
            </x-alert>
        @endif

        <x-form wire:submit="updatePassword">
            <div class="space-y-4">
                <x-password
                    label="Current password"
                    wire:model="current_password"
                    class="input-primary"
                    required
                    autocomplete="current-password"
                    autofocus
                />

                <x-password
                    label="New Password"
                    wire:model="password"
                    class="input-primary"
                    required
                    autocomplete="new-password"
                />

                <x-password
                    label="Confirm Password"
                    wire:model="password_confirmation"
                    class="input-primary"
                    required
                    autocomplete="new-password"
                />
            </div>

            <x-slot:actions>
                <x-button
                    type="submit"
                    label="Save"
                    class="btn-primary"
                    spinner="updatePassword"
                />
            </x-slot:actions>
        </x-form>
    </x-core::profile.layout>
</section>


