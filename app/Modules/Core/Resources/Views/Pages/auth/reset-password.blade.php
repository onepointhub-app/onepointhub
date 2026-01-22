<x-layouts::auth>
    <div>
        <x-auth-header
            title="Reset Password"
            description="Enter your new password below."
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <x-form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <div class="space-y-4">
                <x-input
                    label="Email"
                    name="email"
                    type="email"
                    required
                    class="input-primary focus-within:outline-1"
                    autocomplete="email"
                    icon="o-envelope"
                    value="{{ request('email') }}"
                />

                <x-password
                    label="Password"
                    name="password"
                    placeholder="••••••••"
                    required
                    class="input-primary focus-within:outline-1"
                    autocomplete="new-password"
                    autofocus
                />

                <x-password
                    label="Confirm Password"
                    name="password_confirmation"
                    placeholder="••••••••"
                    required
                    class="input-primary focus-within:outline-1"
                    autocomplete="new-password"
                    autofocus
                />
            </div>

            <x-slot:actions>
                <div class="flex flex-col w-full space-y-4">
                    <x-button label="Reset Password" type="submit" class="btn-primary w-full" />

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
