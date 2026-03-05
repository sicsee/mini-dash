@php
    $type = session()->has('success') ? 'success'
        : (session()->has('error') ? 'error'
        : 'warning');

    $message = session($type);

    $styles = [
        'success' => 'border-green-500 bg-green-50 text-green-800',
        'error' => 'border-red-500 bg-red-50 text-red-800',
        'warning' => 'border-yellow-500 bg-yellow-50 text-yellow-800',
    ];
@endphp


@if(session()->has('success') || session()->has('error') || session()->has('warning'))

<div
    id="toast"
    class="fixed right-5 top-20 flex items-center gap-3 
    border-l-4 {{ $styles[$type] }}
    shadow-lg rounded-lg
    px-5 py-4
    min-w-[280px]
    animate-toast-in
    backdrop-blur-sm
"
>

    {{-- ICON --}}
    <div class="shrink-0">
        @switch($type)
            @case('success')
                <x-lucide-check class="w-6 h-6"/>
                @break

            @case('error')
                <x-lucide-ban class="w-6 h-6"/>
                @break

            @case('warning')
                <x-lucide-triangle-alert class="w-6 h-6"/>
                @break
        
            @default
                <x-lucide-circle-alert class="w-6 h-6"/>
        @endswitch
    </div>

    {{-- MESSAGE --}}
    <p class="text-sm font-medium">
        {{ $message }}
    </p>

</div>

@endif