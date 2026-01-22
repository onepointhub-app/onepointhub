<div class="flex items-start max-md:flex-col gap-6">
    <div class="w-full pb-4 md:w-55 md:shrink-0">
        <x-menu activate-by-route>
            <x-menu-item
                title="Profile"
                icon="o-user"
                :link="route('profile.edit')"
            />
            <x-menu-item
                title="Password"
                icon="o-key"
                :link="route('profile.password.edit')"
            />
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <x-menu-item
                    title="Two-Factor Auth"
                    icon="o-shield-check"
                    :link="route('two-factor.show')"
                />
            @endif
        </x-menu>
    </div>

    <div class="flex-1 self-stretch max-md:pt-6 min-w-0">
        <x-header
            :title="$heading ?? ''"
            :subtitle="$subheading ?? ''"
            separator
        />

        <div class="mt-6 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
