@props(['title', 'status', 'amount', 'icon', 'color'])

@php
    $colors = [
        'blue' => 'bg-blue-50 text-blue-600 border-blue-100',
        'zinc' => 'bg-zinc-50 text-zinc-600 border-zinc-100',
    ];
@endphp

<div class="flex items-center justify-between">
    <div class="flex items-center gap-4">
        <div
            class="w-11 h-11 rounded-2xl flex items-center justify-center border {{ $colors[$color] ?? $colors['zinc'] }}">
            @if ($icon == 'home')
                <x-lucide-home class="w-5 h-5" />
            @else
                <x-lucide-car class="w-5 h-5" />
            @endif
        </div>
        <div>
            <h5 class="text-sm font-bold text-zinc-800">{{ $title }}</h5>
            <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-tight">{{ $status }}</p>
        </div>
    </div>
    <span class="text-sm font-black text-zinc-900">{{ $amount }}</span>
</div>
