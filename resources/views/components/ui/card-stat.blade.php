@props(['title', 'value', 'icon', 'trend' => null, 'color' => 'text-zinc-900'])

<div class="bg-white p-8 rounded-[40px] border border-zinc-100 shadow-sm hover:border-zinc-300 transition-all group">
    <div class="flex justify-between items-start">
        <div
            class="w-14 h-14 rounded-2xl bg-zinc-50 flex items-center justify-center text-zinc-900 border border-zinc-100 group-hover:scale-110 transition-transform">
            <x-dynamic-component :component="'lucide-' . $icon" class="w-6 h-6" />
        </div>
        @if ($trend)
            <span
                class="text-[10px] font-black text-green-500 bg-green-50 px-2 py-1 rounded-lg">{{ $trend }}</span>
        @endif
    </div>
    <div class="mt-6">
        <h4 class="text-3xl font-black {{ $color }} tracking-tighter">{{ $value }}</h4>
        <p class="text-[11px] text-zinc-400 font-bold uppercase tracking-widest mt-2">{{ $title }}</p>
    </div>
</div>
