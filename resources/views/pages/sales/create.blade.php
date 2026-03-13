<x-layouts.layout-dash>

    <main 
        x-data="saleManager(@json($products->mapWithKeys(fn($p) => [$p->id => (float) $p->price])))"
        class="container-dash"
    >
        {{-- Cabeçalho com Card de Estatística --}}
        <header class="flex flex-col items-center w-full max-w-7xl mt-10 gap-10">
            <x-ui.card class="w-full">
                <div class="flex items-center justify-between">
                    <x-ui.card-title>
                        Total de vendas: {{ auth()->user()->sales()->count() }}
                    </x-ui.card-title>
                    <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-zinc-900 text-white">
                        <x-lucide-shopping-cart class="w-6 h-6"/>
                    </div>
                </div>
            </x-ui.card>

            <div class="flex justify-between items-center w-full">
                <h1 class="text-2xl font-semibold text-zinc-900 tracking-tight">Vendas</h1>
                <button
                    @click="openCreate()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-zinc-900 text-white rounded-xl hover:bg-zinc-800 transition-all font-medium shadow-sm"
                >
                    <x-lucide-plus class="w-4 h-4"/>
                    Registrar venda
                </button>
            </div>
        </header>

        {{-- Tabela Listagem --}}
        <section class="bg-white rounded-2xl shadow-sm border border-zinc-200 overflow-hidden w-full mt-6">
            <table class="min-w-full border-collapse">
                <thead class="bg-zinc-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider border-r border-zinc-100">Cliente</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider border-r border-zinc-100">Data</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider border-r border-zinc-100">Total</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider border-r border-zinc-100">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-zinc-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse ($sales as $sale)
                        <tr class="hover:bg-zinc-50/50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-zinc-900 border-r border-zinc-100">{{ $sale->customer->name }}</td>
                            <td class="px-6 py-4 text-sm text-zinc-600 border-r border-zinc-100">{{ dataFormatada($sale->sale_date) }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-zinc-900 border-r border-zinc-100">R$ {{ number_format($sale->total_amount, 2, ',', '.') }}</td>
                            <td class="px-6 py-4 border-r border-zinc-100">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-tight
                                    {{ $sale->status === 'concluida' ? 'bg-emerald-50 text-emerald-700' : ($sale->status === 'pendente' ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') }}">
                                    {{ $sale->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button @click="openEdit({{ $sale->load('items') }})" class="p-2 text-zinc-400 hover:text-zinc-900 hover:bg-zinc-100 rounded-lg transition-all">
                                        <x-lucide-pencil class="w-4 h-4"/>
                                    </button>
                                    <form action="{{ route('sales.destroy', $sale) }}" method="POST" onsubmit="return confirm('Deseja excluir?')">
                                        @csrf @method('DELETE')
                                        <button class="p-2 text-zinc-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                            <x-lucide-trash class="w-4 h-4"/>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-zinc-400">Nenhuma venda registrada.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        {{-- Modal de Formulário (Criação e Edição) --}}
        <x-ui.modal name="sale-modal">
            <div class="px-8 pt-6">
                <h2 class="text-lg font-bold text-zinc-900" 
                    x-text="formData.id ? 'Editar Venda #' + formData.id : 'Registrar Venda'">
                </h2>
            </div>
        
            <form 
                :action="formData.id ? `/dashboard/sales/${formData.id}` : '{{ route('sales.store') }}'" 
                method="POST" 
                class="p-8 space-y-8"
            >
                @csrf
                <template x-if="formData.id">
                    <input type="hidden" name="_method" value="PUT">
                </template>
        
                <select x-model="formData.customer_id" name="customer_id" ...>
                <input type="date" x-model="formData.sale_date" name="sale_date" ...>
                <select x-model="formData.status" name="status" ...>
                <textarea x-model="formData.notes" name="notes" ...></textarea>
        
                </form>
        </x-ui.modal>
    </main>

</x-layouts.layout-dash>