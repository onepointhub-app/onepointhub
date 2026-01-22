<?php

use App\Modules\Core\Concerns\ProfileValidationRules;
use App\Modules\Core\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    use ProfileValidationRules;

    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var array<string, mixed> $validated */
        $validated = $this->validate($this->profileRules($user->id));

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        Session::flash('status', 'profile-updated');
        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('home', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        /** @var User $user */
        $user = Auth::user();

        return ! $user->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        /** @var User $user */
        $user = Auth::user();

        return $user->hasVerifiedEmail();
    }
}
?>

<section class="w-full">
    <x-core::profile.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">
        @if (session('status') === 'profile-updated')
            <x-alert icon="o-check-circle" class="alert-success mb-4">
                <p class="font-medium">
                    Profile updated successfully.
                </p>
            </x-alert>
        @endif

        <x-form wire:submit="updateProfileInformation">
            <div class="space-y-4">
                <x-input
                    label="Name"
                    wire:model="name"
                    class="input-primary focus-within:outline-1"
                    required
                    autocomplete="name"
                    autofocus
                />

                <x-input
                    label="Email"
                    wire:model="email"
                    type="email"
                    class="input-primary focus-within:outline-1"
                    required
                    autocomplete="email"
                />

                @if ($this->hasUnverifiedEmail)
                    <x-alert icon="o-exclamation-triangle" class="alert-warning">
                        <div class="space-y-2">
                            <p class="font-medium">
                                Your email address is unverified.
                            </p>
                            <p class="text-sm">
                                <button
                                    type="button"
                                    wire:click="resendVerificationNotification"
                                    class="link link-hover link-primary font-medium"
                                >
                                    Click here to re-send the verification email.
                                </button>
                            </p>
                            @if (session('status') === 'verification-link-sent')
                                <x-alert icon="o-check-circle" class="alert-success mt-2">
                                    <p class="text-sm">
                                        A new verification link has been sent to your email address.
                                    </p>
                                </x-alert>
                            @endif
                        </div>
                    </x-alert>
                @endif
            </div>

            <x-slot:actions>
                <x-button
                    type="submit"
                    label="Save"
                    class="btn-primary"
                    spinner="updateProfileInformation"
                />
            </x-slot:actions>
        </x-form>

        @if ($this->showDeleteUser)
            <livewire:core::profile.delete-user-form />
        @endif
    </x-core::profile.layout>
</section>
