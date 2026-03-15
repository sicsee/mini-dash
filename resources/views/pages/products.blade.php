<x-layouts.layout-dash>
    <main x-data="{
        activeModal: null,
        currentProduct: { id: null, name: '', price: '' }
    }"
        class="min-h-screen lg:h-screen bg-white flex flex-col px-6 lg:px-12 py-8 lg:py-10 font-sans antialiased text-black overflow-x-hidden lg:overflow-hidden">

        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 lg:mb-12 gap-6">
            <div>
                <h1 class="text-3xl font-black tracking-tight">Produtos</h1>
                <p class="text-xs text-zinc-400 font-bold uppercase tracking-widest mt-1">Gerenciamento de Inventário</p>
            </div>

            <div class="flex items-center justify-between w-full md:w-auto gap-6">
                <div class="text-left md:text-right border-r border-zinc-100 pr-6">
                    <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Total itens</p>
                    <p class="text-lg font-black">{{ auth()->user()->total_products }}</p>
                </div>
                <button @click="activeModal = 'product-create'"
                    class="h-12 px-6 lg:px-8 bg-black text-white rounded-xl text-[10px] lg:text-xs font-black uppercase tracking-widest hover:bg-zinc-800 transition-all shadow-lg shadow-zinc-200">
                    + <span class="hidden sm:inline">Novo Produto</span><span class="sm:hidden">Novo</span>
                </button>
            </div>
        </header>

        <section class="hidden lg:flex flex-col bg-white border border-zinc-100 rounded-3xl shadow-sm overflow-hidden">
            <div class="overflow-y-auto max-h-[calc(100vh-300px)] custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 bg-zinc-50 border-b border-zinc-100 z-10">
                        <tr>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-zinc-400">Produto
                            </th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-zinc-400">Valor
                                Unitário</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-zinc-400">Qtd.
                                Estoque</th>
                            <th
                                class="px-8 py-5 text-right text-[10px] font-black uppercase tracking-widest text-zinc-400">
                                Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50">
                        @forelse ($products as $p)
                            <tr class="hover:bg-zinc-50/50 transition-colors group">
                                <td class="px-8 py-5 font-bold text-sm text-zinc-900">{{ $p->name }}</td>
                                <td class="px-8 py-5 font-black text-sm text-zinc-900">R$
                                    {{ number_format($p->price, 2, ',', '.') }}</td>
                                <td class="px-8 py-5">
                                    <div
                                        class="flex items-center gap-2 font-bold text-sm {{ $p->stock->quantity <= 5 ? 'text-red-600' : 'text-zinc-900' }}">
                                        {{ $p->stock->quantity }} un.
                                        @if ($p->stock->quantity <= 5)
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-5 text-right">
                                    <div class="justify-end gap-4 flex items-center">
                                        <button
                                            @click="currentProduct = { id: {{ $p->id }}, name: '{{ addslashes($p->name) }}', price: '{{ $p->price }}' }; activeModal = 'product-edit';"
                                            class="text-[10px] font-black uppercase text-zinc-400 hover:text-black transition-colors">Editar</button>
                                        <form class="sm:mb-1" action="{{ route('products.destroy', $p) }}"
                                            method="POST" onsubmit="return confirm('Excluir?')">
                                            @csrf @method('DELETE')
                                            <button
                                                class="text-[10px] font-black uppercase text-zinc-300 hover:text-red-600 transition-colors">Excluir</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="lg:hidden space-y-4 pb-20">
            @forelse ($products as $p)
                <div class="p-6 bg-white border border-zinc-100 rounded-[24px] shadow-sm flex flex-col gap-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-base font-black text-zinc-900">{{ $p->name }}</h3>
                            <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mt-1">Ref:
                                #PROD{{ $p->id }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black text-zinc-900">R$ {{ number_format($p->price, 2, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-zinc-50">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-black uppercase text-zinc-400">Estoque:</span>
                            <span
                                class="text-sm font-bold {{ $p->stock->quantity <= 5 ? 'text-red-600' : 'text-zinc-900' }}">
                                {{ $p->stock->quantity }} un.
                            </span>
                        </div>
                        <div class="flex gap-4">
                            <button
                                @click="currentProduct = { id: {{ $p->id }}, name: '{{ addslashes($p->name) }}', price: '{{ $p->price }}' }; activeModal = 'product-edit';"
                                class="p-2 text-zinc-400 hover:text-black"><x-lucide-pencil class="w-4 h-4" /></button>

                            <form action="{{ route('products.destroy', $p) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="p-2 text-zinc-300 hover:text-red-600"><x-lucide-trash-2
                                        class="w-4 h-4" /></button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-20 text-center text-zinc-300 font-medium">Nenhum produto.</div>
            @endforelse
        </section>

        <template x-if="activeModal === 'product-create' || activeModal === 'product-edit'">
            <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/40 backdrop-blur-sm p-0 sm:p-4"
                @click.self="activeModal = null">

                <div class="bg-white w-full max-w-lg rounded-t-[32px] sm:rounded-[32px] shadow-2xl overflow-hidden border border-zinc-100"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-4">

                    <div class="px-8 py-6 border-b border-zinc-50 flex justify-between items-center">
                        <h2 class="text-xl font-black tracking-tight"
                            x-text="activeModal === 'product-create' ? 'Novo Produto' : 'Editar Produto'"></h2>
                        <button @click="activeModal = null"
                            class="w-8 h-8 flex items-center justify-center bg-zinc-50 rounded-full text-zinc-300 hover:text-black transition-colors">
                            <x-lucide-x class="w-4 h-4" />
                        </button>
                    </div>

                    <form class="p-8 space-y-6"
                        :action="activeModal === 'product-create' ? '{{ route('products.store') }}' :
                            `/dashboard/products/${currentProduct.id}`"
                        method="POST">
                        @csrf
                        <template x-if="activeModal === 'product-edit'">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Nome do
                                Produto</label>
                            <input type="text" name="name" x-model="currentProduct.name" required
                                class="w-full h-12 px-4 rounded-xl border border-zinc-100 bg-zinc-50 focus:bg-white focus:border-black transition-all text-sm font-bold outline-none">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Preço de
                                Venda</label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-bold text-zinc-300">R$</span>
                                <input type="number" name="price" step="0.01" x-model="currentProduct.price"
                                    required
                                    class="w-full h-12 pl-12 pr-4 rounded-xl border border-zinc-100 bg-zinc-50 focus:bg-white focus:border-black transition-all text-sm font-bold outline-none">
                            </div>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" @click="activeModal = null"
                                class="flex-1 h-12 text-xs font-black uppercase tracking-widest text-zinc-400 hover:text-black transition-all">Cancelar</button>
                            <button type="submit"
                                class="flex-1 h-12 bg-black text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-zinc-800 shadow-lg transition-all">Confirmar</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </main>
</x-layouts.layout-dash>
