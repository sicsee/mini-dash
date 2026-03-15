@props(['title', 'date', 'amount', 'icon'])

<div class="flex items-center justify-between group cursor-pointer hover:bg-zinc-50 p-2 rounded-2xl transition-all">
    <div class="flex items-center gap-4">
        <div
            class="w-10 h-10 rounded-xl bg-white border border-zinc-100 shadow-sm flex items-center justify-center text-zinc-400 group-hover:text-zinc-900 transition-colors">
            @if ($icon == 'file-text')
                <x-lucide-file-text class="w-5 h-5" />
            @else
                <x-lucide-clock class="w-5 h-5" />
            @endif
        </div>
        <div>
            <h5 class="text-sm font-bold text-zinc-800">{{ $title }}</h5>
            <p class="text-[10px] text-zinc-400 font-medium">{{ $date }}</p>
        </div>
    </div>
    <span class="text-sm font-black text-zinc-900">{{ $amount }}</span>
</div>
