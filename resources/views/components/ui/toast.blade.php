@php
    $type = session()->has('success')
        ? 'success'
        : (session()->has('error')
            ? 'error'
            : (session()->has('warning')
                ? 'warning'
                : null));

    $message = $type ? session($type) : null;

    $config = [
        'success' => ['class' => 'bg-zinc-950 text-white', 'icon' => 'lucide-check-circle', 'label' => 'Sucesso'],
        'error' => ['class' => 'bg-rose-600 text-white', 'icon' => 'lucide-ban', 'label' => 'Erro'],
        'warning' => ['class' => 'bg-amber-500 text-black', 'icon' => 'lucide-triangle-alert', 'label' => 'Atenção'],
    ];
@endphp

@if ($message)
    <div x-data="{
        show: false,
        init() {
            setTimeout(() => this.show = true, 100);
            setTimeout(() => this.show = false, 5000);
        }
    }" x-show="show" x-cloak x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="translate-y-12 opacity-0 scale-90"
        x-transition:enter-end="translate-y-0 opacity-100 scale-100" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
        class="fixed bottom-10 right-6 z-100 flex items-center gap-4 px-6 py-4 rounded-[24px] shadow-2xl shadow-black/20 {{ $config[$type]['class'] }} border border-white/10">
        {{-- Ícone com Círculo de destaque --}}
        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-white/20">
            @if ($type === 'success')
                <x-lucide-check class="w-5 h-5 text-white" />
            @elseif($type === 'error')
                <x-lucide-ban class="w-5 h-5 text-white" />
            @else
                <x-lucide-triangle-alert class="w-5 h-5 text-black" />
            @endif
        </div>

        <div>
            <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-70 leading-none mb-1">
                {{ $config[$type]['label'] }}
            </p>
            <p class="text-sm font-bold tracking-tight">
                {{ $message }}
            </p>
        </div>

        {{-- Botão de fechar manual --}}
        <button @click="show = false" class="ml-4 opacity-50 hover:opacity-100 transition-opacity">
            <x-lucide-x class="w-4 h-4" />
        </button>
    </div>
@endif
