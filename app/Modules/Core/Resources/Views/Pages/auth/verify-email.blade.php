<x-layouts::auth>
    <div>
        <x-auth-header
            title="Verify your Email"
            description="We've sent a verification link to"
        />

        <p class="text-center text-primary font-medium mb-8">
            {{ request()->user()->email }}
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-success">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="text-center">
            <p class="text-sm text-base-content/70 mb-4">
                Didn't receive the email?
            </p>

            <x-form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <x-button label="Resend Verification Email" type="submit" class="btn-primary w-full" />
            </x-form>
        </div>

        <div class="text-center pt-6">
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
</x-layouts::auth>
