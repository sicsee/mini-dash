<x-layouts.layout-dash>
    <main x-data="{
        activeModal: null,
        currentProduct: { id: null, name: '', quantity: '' }
    }"
        class="min-h-screen lg:h-screen bg-white flex flex-col px-6 lg:px-12 py-8 lg:py-10 font-sans antialiased text-black overflow-x-hidden lg:overflow-hidden">

        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 lg:mb-12 gap-6">
            <div>
                <h1 class="text-3xl font-black tracking-tight">Estoque</h1>
                <p class="text-xs text-zinc-400 font-bold uppercase tracking-widest mt-1">Controle de Ativos</p>
            </div>

            <div class="flex items-center justify-between w-full md:w-auto gap-6">
                <div class="text-left md:text-right border-r border-zinc-100 pr-6">
                    <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Itens Totais</p>
                    <p class="text-lg font-black">{{ auth()->user()->total_items }}</p>
                </div>
                <button @click="activeModal = 'stock-create'"
                    class="h-12 px-6 lg:px-8 bg-black text-white rounded-xl text-[10px] lg:text-xs font-black uppercase tracking-widest hover:bg-zinc-800 transition-all shadow-lg shadow-zinc-200">
                    + <span class="hidden sm:inline">Adicionar Quantidade</span><span class="sm:hidden">Adicionar</span>
                </button>
            </div>
        </header>

        <section class="hidden lg:block bg-white border border-zinc-100 rounded-3xl shadow-sm overflow-hidden">
            <div class="overflow-y-auto max-h-[calc(100vh-300px)] custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 bg-zinc-50 border-b border-zinc-100 z-10">
                        <tr>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-zinc-400">Produto
                            </th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-zinc-400">
                                Quantidade Atual</th>
                            <th
                                class="px-8 py-5 text-right text-[10px] font-black uppercase tracking-widest text-zinc-400">
                                Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50">
                        @forelse ($stocks as $s)
                            <tr class="hover:bg-zinc-50/50 transition-colors group">
                                <td class="px-8 py-5">
                                    <span class="text-sm font-bold text-zinc-900">{{ $s->product->name }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-sm font-black {{ $s->quantity <= 5 ? 'text-red-600' : 'text-zinc-900 font-bold' }}">
                                            {{ $s->quantity }} un.
                                        </span>
                                        @if ($s->quantity <= 5)
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <button
                                        @click="currentProduct = { id: {{ $s->id }}, name: '{{ addslashes($s->product->name) }}', quantity: {{ $s->quantity }} }; activeModal = 'stock-edit';"
                                        class="text-[10px] font-black uppercase text-zinc-400 hover:text-black transition-colors">
                                        Editar Saldo
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-8 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <p class="py-20 text-center text-zinc-400 font-bold">Nenhum item no estoque</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="lg:hidden space-y-4 pb-20">
            @forelse ($stocks as $s)
                <div class="p-6 bg-white border border-zinc-100 rounded-[24px] shadow-sm flex flex-col gap-4">
                    <div class="flex justify-between items-start">
                        <div class="max-w-[70%]">
                            <h3 class="text-sm font-black text-zinc-900 leading-snug">{{ $s->product->name }}</h3>
                            <p class="text-[9px] font-black text-zinc-400 uppercase tracking-widest mt-1">SKU:
                                #{{ str_pad($s->product->id, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="text-right">
                            <div class="flex items-center gap-2">
                                @if ($s->quantity <= 5)
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                @endif
                                <span
                                    class="text-base font-black {{ $s->quantity <= 5 ? 'text-red-600' : 'text-zinc-950' }}">
                                    {{ $s->quantity }}
                                </span>
                            </div>
                            <p class="text-[9px] font-black text-zinc-400 uppercase tracking-tighter">unidades</p>
                        </div>
                    </div>

                    <button
                        @click="currentProduct = { id: {{ $s->id }}, name: '{{ addslashes($s->product->name) }}', quantity: {{ $s->quantity }} }; activeModal = 'stock-edit';"
                        class="w-full h-10 flex items-center justify-center bg-zinc-50 rounded-xl text-[10px] font-black uppercase tracking-widest text-zinc-600 active:bg-zinc-950 active:text-white transition-all">
                        Ajustar Saldo
                    </button>
                </div>
            @empty
                <p class="py-20 text-center text-zinc-300 font-medium">Nenhum item em estoque</p>
            @endforelse
        </section>

        <template x-if="activeModal === 'stock-create' || activeModal === 'stock-edit'">
            <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/40 backdrop-blur-sm p-0 sm:p-4"
                @click.self="activeModal = null">

                <div class="bg-white w-full max-w-lg rounded-t-[32px] sm:rounded-[32px] shadow-2xl overflow-hidden border border-zinc-100"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-4">

                    <div class="px-8 py-6 border-b border-zinc-50 flex justify-between items-center">
                        <h2 class="text-xl font-black tracking-tight"
                            x-text="activeModal === 'stock-create' ? 'Ajustar Estoque' : 'Editar Saldo'"></h2>
                        <button @click="activeModal = null"
                            class="w-8 h-8 flex items-center justify-center bg-zinc-50 rounded-full text-zinc-300 hover:text-black transition-colors">
                            <x-lucide-x class="w-4 h-4" />
                        </button>
                    </div>

                    <form class="p-8 space-y-6"
                        :action="activeModal === 'stock-create' ? '{{ route('stocks.store') }}' :
                            `/dashboard/stocks/${currentProduct.id}`"
                        method="POST">
                        @csrf
                        <template x-if="activeModal === 'stock-edit'">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="space-y-1.5">
                            <label
                                class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Produto</label>
                            <template x-if="activeModal === 'stock-create'">
                                <select name="product_id"
                                    class="w-full h-12 px-4 rounded-xl border border-zinc-700 bg-zinc-50 focus:bg-white focus:border-black transition-all text-sm font-bold outline-none appearance-none">
                                    @foreach ($stocks as $s)
                                        <option value="{{ $s->product->id }}">{{ $s->product->name }}</option>
                                    @endforeach
                                </select>
                            </template>
                            <template x-if="activeModal === 'stock-edit'">
                                <input type="text" x-model="currentProduct.name" disabled
                                    class="w-full h-12 px-4 rounded-xl border border-zinc-100 bg-zinc-100 text-zinc-400 text-sm font-bold outline-none cursor-not-allowed">
                            </template>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Quantidade em
                                Estoque</label>
                            <input type="number" name="quantity" x-model="currentProduct.quantity" required
                                class="w-full h-12 px-4 rounded-xl border border-zinc-700 bg-zinc-50 focus:bg-white focus:border-black transition-all text-sm font-bold outline-none">
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" @click="activeModal = null"
                                class="flex-1 h-12 text-xs font-black uppercase tracking-widest text-zinc-400 hover:text-black">Cancelar</button>
                            <button type="submit" class="flex-1 h-12 bg-black text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-zinc-800 transition-all shadow-lg">Salvar
                                Saldo</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </main>
</x-layouts.layout-dash>
