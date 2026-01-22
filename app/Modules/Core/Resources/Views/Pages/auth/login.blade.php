<x-layouts::auth>
    <div>
        <x-auth-header
            title="OnePointHub"
            description="Welcome back! Please login to your account."
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <x-form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div class="space-y-4">
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
                    :value="old('email')"
                />

                <x-password
                    label="Password"
                    name="password"
                    placeholder="••••••••"
                    required
                    class="input-primary focus-within:outline-1"
                    autocomplete="current-password"
                />
            </div>

            <div class="flex items-center justify-between mt-4">
                <x-checkbox
                    label="Remember me"
                    name="remember"
                    class="text-primary"
                />

                @if (Route::has('password.request'))
                    <a
                        wire:navigate
                        href="{{ route('password.request') }}"
                        class="text-sm link link-hover link-primary"
                    >
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <x-slot:actions>
                <div class="flex flex-col w-full space-y-4">
                    <x-button label="Login" type="submit" class="btn-primary w-full" />

                    @if (Route::has('register'))
                        <div class="items-center flex w-full">
                            <span class="text-sm text-base-content/70 mr-2">
                                {{ __('Don\'t have an account?') }}
                            </span>

                            <a
                                wire:navigate
                                href="{{ route('register') }}"
                                class="link link-hover link-primary font-medium text-sm"
                            >
                                {{ __('Sign up') }}
                            </a>
                        </div>
                    @endif
                </div>
            </x-slot:actions>
        </x-form>
    </div>
</x-layouts::auth>
