@php
    $type = session()->has('success') ? 'success'
        : (session()->has('error') ? 'error'
        : 'warning');

    $message = session($type);

    $styles = [
        'success' => 'bg-green-100 border-green-400 text-green-700',
        'error' => 'bg-red-100 border-red-400 text-red-700',
        'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
    ];
@endphp


@if(session()->has('success') || session()->has('error') || session()->has('warning'))
    <div id="toast" class="absolute right-0 top-20 m-4 flex items-center gap-2 border-2 p-3 mb-4 {{ $styles[$type] }} transition-all ease-linear">

        {{-- ICON --}}
        @switch($type)
            @case('success')
                <x-lucide-check class="w-7 h-7"/>
                @break

            @case('error')
                <x-lucide-ban class="w-7 h-7"/>
                @break

            @case('warning')
                <x-lucide-triangle-alert class="w-7 h-7"/>
                @break
        
            @default <x-lucide-circle-alert class="w-7 h-7"/>
                
        @endswitch

        {{-- MESSAGE --}}
        <p>
            {{ $message }}
        </p>
    </div>

@endif

