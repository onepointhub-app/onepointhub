<x-layouts::auth>
    <div>
        <x-auth-header
            title="Confirm Password"
            description="This is a secure area of the application. Please confirm your password before continuing."
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <x-form method="POST" action="{{ route('password.confirm.store') }}">
            @csrf

            <div>
                <x-password
                    label="Password"
                    name="password"
                    placeholder="••••••••"
                    required
                    class="input-primary focus-within:outline-1"
                    autocomplete="current-password"
                    autofocus
                />
            </div>

            <x-slot:actions>
                <x-button label="Confirm" type="submit" class="btn-primary w-full" />
            </x-slot:actions>
        </x-form>
    </div>
</x-layouts::auth>
