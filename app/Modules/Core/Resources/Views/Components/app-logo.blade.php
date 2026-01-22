<a href="/" wire:navigate class="flex items-center">
    <!-- Hidden when collapsed -->
    <div {{ $attributes->class(["hidden-when-collapsed"]) }}>
        <div class="flex items-center gap-3 w-fit">
            <img src="{{ asset('svg/logo-icon-only.svg') }}" alt="OnePointHub" class="w-10 h-10" />
            <span class="font-bold text-2xl text-primary">
                OnePointHub
            </span>
        </div>
    </div>

    <!-- Display when collapsed -->
    <div class="display-when-collapsed hidden mx-5 mt-5 mb-1 h-7">
        <img src="{{ asset('svg/logo-icon-only.svg') }}" alt="OnePointHub" class="w-7 h-7" />
    </div>
</a>
