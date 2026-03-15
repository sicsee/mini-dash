<x-layouts.layout-dash>
    {{-- Fundo Branco Puro e Ajuste de Altura para Mobile --}}
    <div
        class="min-h-screen lg:h-screen bg-white flex flex-col p-6 lg:p-12 font-sans antialiased text-zinc-950 overflow-x-hidden lg:overflow-hidden selection:bg-zinc-950 selection:text-white">

        <header
            class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-10 lg:mb-16 border-b border-zinc-50 pb-8 gap-6 lg:gap-0">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <x-lucide-globe class="w-4 h-4 text-zinc-900" />
                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-900">Mini Dash</span>
                </div>
                <h1 class="text-3xl lg:text-4xl font-extrabold tracking-tighter text-zinc-950">Dashboard</h1>
            </div>

            <div class="flex items-center justify-between w-full lg:w-auto gap-6">
                <div class="text-left lg:text-right">
                    <p class="text-xs font-bold text-zinc-900">{{ now()->translatedFormat('d F, Y') }}</p>
                    <p class="text-[9px] text-zinc-400 uppercase font-black tracking-widest mt-1">Dados Sincronizados</p>
                </div>
                <div class="relative group">
                    <div
                        class="absolute -inset-1 bg-linear-to-r from-zinc-200 to-zinc-100 rounded-full blur opacity-25 group-hover:opacity-50 transition duration-1000">
                    </div>
                    <div
                        class="relative w-12 h-12 lg:w-14 lg:h-14 rounded-full bg-white border border-zinc-100 shadow-inner flex items-center justify-center text-zinc-900 font-extrabold text-lg">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        {{-- Grid de Métricas: 1 col no mobile, 2 em tablet, 4 em desktop --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-10 mb-10 lg:mb-20">
            {{-- Receita --}}
            <div
                class="p-6 lg:p-8 border border-zinc-100 rounded-[32px] group hover:border-zinc-200 transition-all flex flex-col justify-between h-40 lg:h-48">
                <div class="flex justify-between items-start">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">Receita Total</p>
                    <x-lucide-badge-dollar-sign
                        class="w-6 h-6 text-amber-500 bg-amber-50 p-1.5 rounded-lg border border-amber-100 shadow-inner" />
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-xs font-bold text-zinc-300">BRL</span>
                    <h3
                        class="text-3xl lg:text-4xl font-black tracking-tight text-zinc-950 group-hover:translate-x-1 transition-transform">
                        {{ number_format($total, 2, ',', '.') }}
                    </h3>
                </div>
            </div>

            {{-- Pedidos --}}
            <div
                class="p-6 lg:p-8 border border-zinc-100 rounded-[32px] group hover:border-zinc-200 transition-all flex flex-col justify-between h-40 lg:h-48">
                <div class="flex justify-between items-start">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">Total Pedidos</p>
                    <x-lucide-box
                        class="w-6 h-6 text-sky-600 bg-sky-50 p-1.5 rounded-lg border border-sky-100 shadow-inner" />
                </div>
                <h3
                    class="text-3xl lg:text-4xl font-black tracking-tight text-zinc-950 group-hover:translate-x-1 transition-transform">
                    {{ $count }}
                </h3>
            </div>

            {{-- Ticket --}}
            <div
                class="p-6 lg:p-8 border border-zinc-100 rounded-[32px] group hover:border-zinc-200 transition-all flex flex-col justify-between h-40 lg:h-48">
                <div class="flex justify-between items-start">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">Ticket Médio</p>
                    <x-lucide-trending-up
                        class="w-6 h-6 text-zinc-600 bg-zinc-50 p-1.5 rounded-lg border border-zinc-100 shadow-inner" />
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-xs font-bold text-zinc-300">BRL</span>
                    <h3
                        class="text-3xl lg:text-4xl font-black tracking-tight text-zinc-950 group-hover:translate-x-1 transition-transform">
                        {{ number_format($avg, 0, ',', '.') }}
                    </h3>
                </div>
            </div>

            {{-- Estoque --}}
            <div
                class="p-6 lg:p-8 border border-zinc-100 rounded-[32px] group hover:border-zinc-200 transition-all flex flex-col justify-between h-40 lg:h-48">
                <div class="flex justify-between items-start">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">Alertas Inventário</p>
                    <x-lucide-warehouse
                        class="w-6 h-6 text-rose-700 bg-rose-50 p-1.5 rounded-lg border border-rose-100 shadow-inner" />
                </div>
                <h3
                    class="text-3xl lg:text-4xl font-black tracking-tight {{ $alert > 0 ? 'text-rose-700' : 'text-zinc-950' }} group-hover:translate-x-1 transition-transform">
                    {{ $alert }}<span
                        class="text-[9px] font-black text-rose-300 ml-2 tracking-widest uppercase">itens</span>
                </h3>
            </div>
        </div>

        {{-- Corpo Principal: Empilha no Mobile, Lado a Lado no Desktop --}}
        <div class="flex-1 grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 min-h-0">

            {{-- Vendas Recentes --}}
            <div class="lg:col-span-8 flex flex-col min-h-0 order-2 lg:order-1">
                <div class="flex justify-between items-center mb-6">
                    <h4 class="text-[11px] font-black uppercase tracking-[0.4em] text-zinc-950">Vendas Recentes</h4>
                    <x-lucide-clipboard-list class="w-4 h-4 text-zinc-300" />
                </div>

                <div class="flex-1 lg:overflow-y-auto lg:pr-6 custom-scrollbar">
                    <table class="w-full">
                        <tbody class="divide-y divide-zinc-50 border-t border-zinc-50">
                            @foreach ($sales as $sale)
                                <tr class="group hover:bg-[#F9F9F9] transition-all duration-300">
                                    <td class="py-5 lg:py-6">
                                        <div class="flex items-center gap-4 lg:gap-5">
                                            <div
                                                class="w-10 h-10 border border-zinc-100 bg-white text-zinc-900 rounded-2xl flex items-center justify-center text-[11px] font-black tracking-tight group-hover:border-zinc-950 transition-colors">
                                                {{ substr($sale->customer->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-zinc-900 tracking-tight">
                                                    {{ $sale->customer->name }}</p>
                                                <p
                                                    class="text-[9px] text-zinc-400 uppercase font-black tracking-[0.2em] mt-1 flex items-center gap-1.5">
                                                    @if ($sale->sale_date->isToday())
                                                        Hoje, {{ $sale->sale_date->format('H:i') }}
                                                    @else
                                                        {{ $sale->sale_date->format('d/m/y') }}
                                                    @endif
                                                    <span class="hidden md:inline">•
                                                        {{ strtoupper($sale->status) }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-5 lg:py-6 text-right">
                                        <p
                                            class="text-sm lg:text-base font-extrabold text-zinc-950 italic tracking-tight">
                                            R$ {{ number_format($sale->total_amount, 2, ',', '.') }}
                                        </p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Comandos e Performance --}}
            <div class="lg:col-span-4 space-y-8 lg:space-y-12 order-1 lg:order-2">
                <div>
                    <h4 class="text-[11px] font-black uppercase tracking-[0.4em] mb-6 text-zinc-950">Comandos</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <button
                            class="group flex flex-col gap-4 text-left p-6 bg-zinc-950 text-white rounded-3xl hover:bg-black transition-all shadow-xl shadow-zinc-200">
                            <x-lucide-receipt
                                class="w-6 h-6 text-white bg-white/10 p-1.5 rounded-lg border border-white/10" />
                            <span
                                class="text-[10px] lg:text-[11px] font-black uppercase tracking-widest leading-4">Nova<br>Venda</span>
                        </button>
                        <button
                            class="group flex flex-col gap-4 text-left p-6 bg-white border border-zinc-100 text-zinc-900 rounded-3xl hover:border-zinc-300 transition-all">
                            <x-lucide-bar-chart-3
                                class="w-6 h-6 text-zinc-600 bg-zinc-50 p-1.5 rounded-lg border border-zinc-100" />
                            <span
                                class="text-[10px] lg:text-[11px] font-black uppercase tracking-widest leading-4">Relatório<br>Estoque</span>
                        </button>
                    </div>
                </div>

                <div class="p-8 lg:p-10 bg-zinc-950 rounded-[40px] text-white relative overflow-hidden group">
                    <div class="relative z-10 flex flex-col justify-between h-full gap-6">
                        <div class="flex justify-between items-center">
                            <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-zinc-600">Performance</h4>
                            <x-lucide-target class="w-4 h-4 text-zinc-600 animate-pulse" />
                        </div>
                        <div class="space-y-3">
                            <p class="text-3xl lg:text-4xl font-extrabold italic tracking-tighter">75% <span
                                    class="text-xs text-zinc-600 font-bold uppercase not-italic tracking-wider ml-1">Meta</span>
                            </p>
                            <div class="w-full bg-zinc-800 h-1.5 rounded-full overflow-hidden">
                                <div
                                    class="bg-white h-full w-3/4 shadow-[0_0_15px_rgba(255,255,255,0.4)] transition-all duration-1000">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.layout-dash>
