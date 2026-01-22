@props([
    'title',
    'description',
])

<div class="flex flex-col items-center mb-6">
    <h1 class="text-2xl font-bold text-base-content">
        {{ $title }}
    </h1>
    <p class="text-base-content/70 mt-2 text-center">
        {{ $description }}
    </p>
</div>
