<?php

use App\Modules\Core\Concerns\PasswordValidationRules;
use App\Modules\Core\Models\User;
use App\Modules\Core\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component {
    use PasswordValidationRules;

    public string $password = '';

    public bool $confirmModal = false;

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => $this->currentPasswordRules(),
        ]);

        /** @var User $user */
        $user = Auth::user();

        tap($user, $logout(...))->delete();

        $this->redirecT('/', navigate: true);
    }
}
?>

<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <x-header title="Delete account" subtitle="Delete your account and all of its resources" />
    </div>

    <x-alert icon="o-exclamation-triangle" class="alert-warning mb-4">
        <div class="space-y-2">
            <p class="font-medium">
                Once your account is deleted, all of its resources and data will be permanently deleted.
            </p>
            <p class="text-sm text-base-content/70">
                Before deleting your account, please download any data or information that you wish to retain.
            </p>
        </div>
    </x-alert>

    <x-modal wire:model="confirmModal" class="backdrop-blur">
        <x-form wire:submit="deleteUser">
            <x-header
                title="Are you sure you want to delete your account?"
                subtitle="Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account."
            />

            <div class="mt-4">
                <x-password
                    label="Password"
                    wire:model="password"
                    class="input-primary"
                    required
                    autocomplete="current-password"
                    autofocus
                />
            </div>

            <x-slot:actions>
                <x-button
                    label="Cancel"
                    class="btn-ghost"
                    wire:click="$set('confirmModal', false)"
                />
                <x-button
                    label="Delete Account"
                    class="btn-error"
                    type="submit"
                    spinner="deleteUser"
                />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <x-button
        label="Delete account"
        class="btn-error"
        @click="$wire.confirmModal = true"
        icon="o-trash"
    />
</section>
