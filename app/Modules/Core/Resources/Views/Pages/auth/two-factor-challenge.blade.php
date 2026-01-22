<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <div
            class="relative w-full h-auto"
            x-cloak
            x-data="{
                showRecoveryInput: @js($errors->has('recovery_code')),
                code: '',
                recovery_code: '',
                toggleInput() {
                    this.showRecoveryInput = !this.showRecoveryInput;

                    this.code = '';
                    this.recovery_code = '';

                    $dispatch('clear-2fa-auth-code');

                    $nextTick(() => {
                        this.showRecoveryInput
                            ? this.$refs.recovery_code?.focus()
                            : $dispatch('focus-2fa-auth-code');
                    });
                },
            }"
        >
            <div x-show="!showRecoveryInput">
                <x-auth-header
                    title="Authentication Code"
                    description="Enter the authentication code provided by your authenticator application."
                />
            </div>

            <div x-show="showRecoveryInput">
                <x-auth-header
                    title="Recovery Code"
                    description="Please confirm access to your account by entering one of your emergency recovery codes."
                />
            </div>

            <x-form method="POST" action="{{ route('two-factor.login.store') }}">
                @csrf

                <div class="space-y-5 text-center">
                    <div x-show="!showRecoveryInput">
                        <div class="flex items-center justify-center my-5">
                            <x-pin
                                x-model="code"
                                name="code"
                                size="6"
                                class="input-primary"
                                numeric=""
                            />
                        </div>
                    </div>

                    <div x-show="showRecoveryInput">
                        <div class="my-5">
                            <x-input
                                name="recovery_code"
                                x-ref="recovery_code"
                                x-bind:required="showRecoveryInput"
                                autocomplete="one-time-code"
                                x-model="recovery_code"
                            />
                        </div>

                        @error('recovery_code')
                        <p class="text-error text-sm">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <x-button label="Continue" class="btn-primary w-full" type="submit" />
                </div>

                <div class="mt-6 space-x-0.5 text-sm leading-5 text-center">
                    <span class="text-base-content/60">
                        or you can
                    </span>
                    <button
                        type="button"
                        class="link link-hover link-primary font-medium underline"
                        @click="toggleInput()"
                    >
                        <span x-show="!showRecoveryInput">login using a recovery code</span>
                        <span x-show="showRecoveryInput">login using an authentication code</span>
                    </button>
                </div>
            </x-form>
        </div>
    </div>
</x-layouts::auth>
