<x-layouts::auth>
    <div>
        <x-auth-header
            title="Create Account"
            description="Join OnePointHub Today."
        />

        <x-form method="POST" action="{{ route('register.store') }}">
            @csrf

            <div class="space-y-4">
                <x-input
                    label="Name"
                    name="name"
                    placeholder="Your Name"
                    required
                    class="input-primary focus-within:outline-1"
                    autocomplete="name"
                    autofocus
                />

                <x-input
                    label="Email"
                    name="email"
                    placeholder="email@example.com"
                    type="email"
                    required
                    class="input-primary focus-within:outline-1"
                    autocomplete="email"
                    icon="o-envelope"
                    :value="old('email')"
                />

                <x-password
                    label="Password"
                    name="password"
                    placeholder="••••••••"
                    required
                    class="input-primary focus-within:outline-1"
                    autocomplete="new-password"
                />

                <x-password
                    label="Confirm Password"
                    name="password_confirmation"
                    placeholder="••••••••"
                    required
                    class="input-primary focus-within:outline-1"
                    autocomplete="new-password"
                />
            </div>

            <x-slot:actions>
                <div class="flex flex-col w-full space-y-4">
                    <x-button label="Create Account" type="submit" class="btn-primary w-full" />

                    <div class="flex w-full">
                        <span class="text-sm text-base-content/70 mr-2">
                            Already have an account?
                        </span>

                        <a
                            wire:navigate
                            href="{{ route('login') }}"
                            class="link link-hover link-primary font-medium text-sm"
                        >
                            {{ __('Sign in') }}
                        </a>
                    </div>
                </div>
            </x-slot:actions>
        </x-form>
    </div>
</x-layouts::auth>
