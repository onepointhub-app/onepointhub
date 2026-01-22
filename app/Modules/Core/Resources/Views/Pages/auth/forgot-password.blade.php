<x-layouts::auth>
    <div>
        <x-auth-header
            title="Forgot Password?"
            description="No worries, we'll send you reset instructions."
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <x-form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div>
                <x-input
                    label="Email"
                    name="email"
                    placeholder="email@example.com"
                    type="email"
                    required
                    class="input-primary focus-within:outline-1"
                    autocomplete="email"
                    autofocus
                    icon="o-envelope"
                />
            </div>

            <x-slot:actions>
                <div class="flex flex-col w-full space-y-4">
                    <x-button label="Send Reset Link" type="submit" class="btn-primary w-full" />

                    <div class="items-center flex w-full">
                        <a
                            wire:navigate
                            href="{{ route('login') }}"
                            class="link link-hover link-primary text-sm flex items-center justify-center"
                        >
                            <x-icon name="o-arrow-left" class="mr-2" />
                            Back to login
                        </a>
                    </div>
                </div>
            </x-slot:actions>
        </x-form>
    </div>
</x-layouts::auth>
