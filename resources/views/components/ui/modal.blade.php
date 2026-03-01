@props([
    'name',        // identificador do modal (ex: product-create)
    'title' => '', // título opcional
])

<div
    x-show="activeModal === '{{ $name }}'"
    x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
    style="display: none;"
    @keydown.escape.window="activeModal = null"
>
    <div
        @click.self="activeModal = null"
        class="absolute inset-0"
    ></div>

    <div
        x-transition.scale
        class="relative bg-white rounded-xl shadow-lg w-full max-w-lg p-6"
    >
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">
                {{ $title }}
            </h2>

            <button
                @click="activeModal = null"
                class="text-gray-400 hover:text-gray-600 text-xl font-bold"
                type="button"
            >
                &times;
            </button>
        </div>

        <!-- Body -->
        <div>
            {{ $slot }}
        </div>
    </div>
</div>