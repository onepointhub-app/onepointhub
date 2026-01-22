<?php

use App\Modules\Core\Models\User;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

new class extends Component {
    #[Locked]
    public bool $twoFactorEnabled;

    #[Locked]
    public bool $requiresConfirmation;

    #[Locked]
    public string $qrCodeSvg = '';

    #[Locked]
    public string $manualSetupKey = '';

    public bool $showModal = false;

    public bool $showVerificationStep = false;

    #[Validate('required|string|size:6', onUpdate: false)]
    public string $code = '';

    /**
     * Mount the component.
     */
    public function mount(DisableTwoFactorAuthentication $disableTwoFactorAuthentication): void
    {
        /** @var User $user */
        $user = auth()->user();

        abort_unless(Features::enabled(Features::twoFactorAuthentication()), Response::HTTP_FORBIDDEN);

        if (Fortify::confirmsTwoFactorAuthentication() && is_null($user->two_factor_confirmed_at)) {
            $disableTwoFactorAuthentication($user);
        }

        $this->twoFactorEnabled = $user->hasEnabledTwoFactorAuthentication();
        $this->requiresConfirmation = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm');
    }

    /**
     * Enable two-factor authentication for the user.
     */
    public function enable(EnableTwoFactorAuthentication $enableTwoFactorAuthentication): void
    {
        /** @var User $user */
        $user = auth()->user();

        $enableTwoFactorAuthentication($user);

        if (! $this->requiresConfirmation) {
            $this->twoFactorEnabled = $user->hasEnabledTwoFactorAuthentication();
        }

        $this->loadSetupData();

        $this->showModal = true;
    }

    /**
     * Load the two-factor authentication setup data for the user.
     */
    private function loadSetupData(): void
    {
        /** @var User $user */
        $user = auth()->user();

        try {
            $this->qrCodeSvg = $user->twoFactorQrCodeSvg();

            /** @var string $setupKey */
            $setupKey = decrypt((string) $user->two_factor_secret);

            $this->manualSetupKey = $setupKey;
        } catch (Exception) {
            $this->addError('setupData', 'Failed to fetch setup data.');

            $this->reset('qrCodeSvg', 'manualSetupKey');
        }
    }

    /**
     * Show the two-factor verification step if necessary.
     */
    public function showVerificationIfNecessary(): void
    {
        if ($this->requiresConfirmation) {
            $this->showVerificationStep = true;

            $this->resetErrorBag();

            return;
        }

        $this->closeModal();
    }

    /**
     * Confirm two-factor authentication for the user.
     */
    public function confirmTwoFactor(ConfirmTwoFactorAuthentication $confirmTwoFactorAuthentication): void
    {
        $this->validate();

        $confirmTwoFactorAuthentication(auth()->user(), $this->code);

        $this->closeModal();

        $this->twoFactorEnabled = true;
    }

    /**
     * Reset the two-factor verification state.
     */
    public function resetVerification(): void
    {
        $this->reset('code', 'showVerificationStep');

        $this->resetErrorBag();
    }

    /**
     * Disable two-factor authentication for the user.
     */
    public function disable(DisableTwoFactorAuthentication $disableTwoFactorAuthentication): void
    {
        $disableTwoFactorAuthentication(auth()->user());

        $this->twoFactorEnabled = false;
    }

    /**
     * Close the two-factor authentication modal.
     */
    public function closeModal(): void
    {
        /** @var User $user */
        $user = auth()->user();

        $this->reset(
            'code',
            'manualSetupKey',
            'qrCodeSvg',
            'showModal',
            'showVerificationStep',
        );

        $this->resetErrorBag();

        if (! $this->requiresConfirmation) {
            $this->twoFactorEnabled = $user->hasEnabledTwoFactorAuthentication();
        }
    }

    /**
     * Get the current modal configuration state.
     *
     * @return array{title: string, description: string, buttonText: string}
     */
    public function getModalConfigProperty(): array
    {
        if ($this->twoFactorEnabled) {
            return [
                'title' => __('Two-Factor Authentication Enabled'),
                'description' => __('Two-factor authentication is now enabled. Scan the QR code or enter the setup key in your authenticator app.'),
                'buttonText' => __('Close'),
            ];
        }

        if ($this->showVerificationStep) {
            return [
                'title' => __('Verify Authentication Code'),
                'description' => __('Enter the 6-digit code from your authenticator app.'),
                'buttonText' => __('Continue'),
            ];
        }

        return [
            'title' => __('Enable Two-Factor Authentication'),
            'description' => __('To finish enabling two-factor authentication, scan the QR code or enter the setup key in your authenticator app.'),
            'buttonText' => __('Continue'),
        ];
    }
}
?>

<section class="w-full">
    <x-core::profile.layout :heading="__('Two Factor Authentication')"
                            :subheading="__('Manage your two-factor authentication settings')">
        <div class="flex flex-col w-full mx-auto space-y-6" wire:cloak>
            @if ($twoFactorEnabled)
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <x-badge :value="__('Enabled')" class="badge-success" />
                    </div>

                    <x-alert class="alert-success">
                        <p class="text-sm">
                            {{ __('With two-factor authentication enabled, you will be prompted for a secure, random pin during login, which you can retrieve from the TOTP-supported application on your phone.') }}
                        </p>
                    </x-alert>

                    <livewire:core::profile.two-factor.recovery-codes :$requiresConfirmation />

                    <div class="flex justify-start">
                        <x-button
                            wire:click="disable"
                            class="btn-error"
                            spinner="disable"
                        >
                            {{ __('Disable 2FA') }}
                        </x-button>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <x-badge :value="__('Disabled')" class="badge-error" />
                    </div>

                    <x-alert class="alert-error">
                        <p>
                            {{ __('When you enable two-factor authentication, you will be prompted for a secure pin during login. This pin can be retrieved from a TOTP-supported application on your phone.') }}
                        </p>
                    </x-alert>

                    <x-button
                        wire:click="enable"
                        class="btn-primary"
                        spinner="enable"
                    >
                        {{ __('Enable 2FA') }}
                    </x-button>
                </div>
            @endif
        </div>
    </x-core::profile.layout>

    <x-modal
        wire:model="showModal"
        @close="closeModal"
    >
        <div class="space-y-6">
            <div class="flex flex-col items-center space-y-4">
                <div class="p-0.5 w-auto rounded-full border border-base-300 bg-base-100 shadow-sm">
                    <div class="p-2.5 rounded-full border border-base-300 overflow-hidden bg-base-200 relative">
                        <div
                            class="flex items-stretch absolute inset-0 w-full h-full divide-x [&>div]:flex-1 divide-base-300 justify-around opacity-50">
                            @for ($i = 1; $i <= 5; $i++)
                                <div></div>
                            @endfor
                        </div>

                        <div
                            class="flex flex-col items-stretch absolute w-full h-full divide-y [&>div]:flex-1 inset-0 divide-base-300 justify-around opacity-50">
                            @for ($i = 1; $i <= 5; $i++)
                                <div></div>
                            @endfor
                        </div>

                        <x-icon name="o-shield-check" class="w-6 h-6 relative z-20 text-primary" />
                    </div>
                </div>

                <div class="space-y-2 text-center">
                    <x-header :title="$this->modalConfig['title']" :subtitle="$this->modalConfig['description']" />
                </div>
            </div>

            @if ($showVerificationStep)
                <div class="space-y-6">
                    <div class="flex flex-col items-center space-y-3">
                        <x-pin
                            numeric
                            size="6"
                            name="code"
                            wire:model="code"
                            class="input-primary"
                            autocomplete="one-time-code"
                        />
                        @error('code')
                        <x-alert icon="o-exclamation-circle" class="alert-error">
                            <p class="text-sm">{{ $message }}</p>
                        </x-alert>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <x-button
                            wire:click="resetVerification"
                            class="btn-ghost"
                        >
                            {{ __('Back') }}
                        </x-button>

                        <x-button
                            wire:click="confirmTwoFactor"
                            class="btn-primary"
                            x-bind:disabled="$wire.code.length < 6"
                            spinner="confirmTwoFactor"
                        >
                            {{ __('Confirm') }}
                        </x-button>
                    </div>
                </div>
            @else
                @error('setupData')
                <x-alert icon="o-exclamation-circle" class="alert-error">
                    <p class="text-sm">{{ $message }}</p>
                </x-alert>
                @enderror

                <div class="flex justify-center">
                    <div class="relative w-64 overflow-hidden border rounded-lg border-base-300 aspect-square">
                        @empty($qrCodeSvg)
                            <div class="absolute inset-0 flex items-center justify-center bg-base-200 animate-pulse">
                                <x-icon name="o-arrow-path" class="w-8 h-8 text-base-content/50 animate-spin" />
                            </div>
                        @else
                            <div class="flex items-center justify-center h-full p-4">
                                <div class="bg-base-100 p-3 rounded shadow-sm">
                                    {!! $qrCodeSvg !!}
                                </div>
                            </div>
                        @endempty
                    </div>
                </div>

                <div class="flex justify-center">
                    <x-button
                        class="btn-primary"
                        :disabled="$errors->has('setupData')"
                        wire:click="showVerificationIfNecessary"
                        spinner="showVerificationIfNecessary"
                    >
                        {{ $this->modalConfig['buttonText'] }}
                    </x-button>
                </div>

                <div class="space-y-4">
                    <div class="relative flex items-center justify-center w-full">
                        <div class="absolute inset-0 w-full h-px top-1/2 bg-base-300"></div>
                        <span class="relative px-2 text-sm bg-base-100 text-base-content/60">
                            {{ __('or, enter the code manually') }}
                        </span>
                    </div>

                    <div
                        class="flex items-center gap-2"
                        x-data="{
                            copied: false,
                            async copy() {
                                try {
                                   await navigator.clipboard.writeText('{{ $manualSetupKey }}');
                                    this.copied = true;
                                    setTimeout(() => this.copied = false, 1500);
                                } catch (e) {
                                    console.warn('Could not copy to clipboard');
                                }
                            }
                        }"
                    >
                        <div class="flex items-stretch w-full border rounded-xl border-base-300">
                            @empty($manualSetupKey)
                                <div class="flex items-center justify-center w-full p-3 bg-base-200">
                                    <x-icon name="o-arrow-path" class="w-5 h-5 text-base-content/50 animate-spin" />
                                </div>
                            @else
                                <x-input
                                    readonly
                                    value="{{ $manualSetupKey }}"
                                    class="input-primary rounded-r-none"
                                />

                                <x-button
                                    @click="copy()"
                                    class="btn-primary rounded-l-none"
                                    icon="o-clipboard-document-list"
                                    x-show="!copied"
                                />
                                <x-button
                                    @click="copy()"
                                    class="btn-success rounded-l-none"
                                    icon="o-check"
                                    x-show="copied"
                                />
                            @endempty
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </x-modal>
</section>
