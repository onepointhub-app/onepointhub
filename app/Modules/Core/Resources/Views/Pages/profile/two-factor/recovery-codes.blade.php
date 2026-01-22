<?php

use App\Modules\Core\Models\User;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component {
    /** @var string[] */
    #[Locked]
    public array $recoveryCodes = [];

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->loadRecoveryCodes();
    }

    /**
     * Generate new recovery codes for the user.
     */
    public function regenerateRecoveryCodes(GenerateNewRecoveryCodes $generateNewRecoveryCodes): void
    {
        $generateNewRecoveryCodes(auth()->user());

        $this->loadRecoveryCodes();
    }

    /**
     * Load the recovery codes for the user.
     */
    private function loadRecoveryCodes(): void
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->hasEnabledTwoFactorAuthentication() && $user->two_factor_recovery_codes) {
            try {
                /** @var string $decrypt */
                $decrypt = decrypt($user->two_factor_recovery_codes);

                /** @var string[] $decode */
                $decode = json_decode($decrypt, true);

                $this->recoveryCodes = $decode;
            } catch (Exception) {
                $this->addError('recoveryCodes', 'Failed to load recovery codes.');

                $this->recoveryCodes = [];
            }
        }
    }

};
?>

<div
    class="py-6 space-y-6 border shadow-sm rounded-xl border-base-300 bg-base-100"
    wire:cloak
    x-data="{ showRecoveryCodes: false }"
>
    <div class="px-6 space-y-2">
        <div class="flex items-center gap-2">
            <x-icon name="o-lock-closed" class="w-5 h-5 text-primary" />
            <x-header :title="__('2FA Recovery Codes')" />
        </div>
        <p class="text-base-content/70">
            {{ __('Recovery codes let you regain access if you lose your 2FA device. Store them in a secure password manager.') }}
        </p>
    </div>

    <div class="px-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <x-button
                x-show="!showRecoveryCodes"
                icon="o-eye"
                class="btn-primary"
                @click="showRecoveryCodes = true;"
                aria-expanded="false"
                aria-controls="recovery-codes-section"
            >
                {{ __('View Recovery Codes') }}
            </x-button>

            <x-button
                x-show="showRecoveryCodes"
                icon="o-eye-slash"
                class="btn-primary"
                @click="showRecoveryCodes = false"
                aria-expanded="true"
                aria-controls="recovery-codes-section"
            >
                {{ __('Hide Recovery Codes') }}
            </x-button>

            @if (filled($recoveryCodes))
                <x-button
                    x-show="showRecoveryCodes"
                    icon="o-arrow-path"
                    class="btn-primary"
                    wire:click="regenerateRecoveryCodes"
                    spinner="regenerateRecoveryCodes"
                >
                    {{ __('Regenerate Codes') }}
                </x-button>
            @endif
        </div>

        <div
            x-show="showRecoveryCodes"
            x-transition
            id="recovery-codes-section"
            class="relative overflow-hidden"
            x-bind:aria-hidden="!showRecoveryCodes"
        >
            <div class="mt-3 space-y-3">
                @error('recoveryCodes')
                <x-alert icon="o-exclamation-circle" class="alert-error">
                    <p class="font-medium">{{ $message }}</p>
                </x-alert>
                @enderror

                @if (filled($recoveryCodes))
                    <div
                        class="grid gap-1 p-4 font-mono text-sm rounded-lg bg-base-200 border border-base-300"
                        role="list"
                        aria-label="Recovery codes"
                    >
                        @foreach($recoveryCodes as $code)
                            <div
                                role="listitem"
                                class="select-text text-base-content"
                                wire:loading.class="opacity-50 animate-pulse"
                            >
                                {{ $code }}
                            </div>
                        @endforeach
                    </div>
                    <p class="text-sm text-base-content/70">
                        {{ __('Each recovery code can be used once to access your account and will be removed after use. If you need more, click Regenerate Codes above.') }}
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

