<x-layouts.layout-dash>
    <main x-data="{
        activeModal: null,
        formData: { id: null, customer_id: '', sale_date: '{{ now()->format('Y-m-d') }}', status: 'concluida', notes: '' },
        items: [],
        productsStock: {{ $products->mapWithKeys(fn($p) => [$p->id => $p->stock->quantity ?? 0]) }},
        productsList: {{ $products->mapWithKeys(fn($p) => [$p->id => $p->price]) }},
    
        openCreate() {
            this.formData = { id: null, customer_id: '', sale_date: '{{ now()->format('Y-m-d') }}', status: 'concluida', notes: '' };
            this.items = [{ product_id: '', quantity: 1, price: 0 }];
            this.activeModal = 'sale-modal';
        },
    
        openEdit(sale) {
            this.formData = {
                id: sale.id,
                customer_id: sale.customer_id,
                sale_date: sale.sale_date.split(' ')[0],
                status: sale.status,
                notes: sale.notes || ''
            };
            this.items = sale.items.map(item => ({
                product_id: item.product_id,
                quantity: parseInt(item.quantity),
                price: parseFloat(item.price_at_sale)
            }));
            this.activeModal = 'sale-modal';
        },
    
        updatePrice(index) {
            const id = this.items[index].product_id;
            if (id && this.productsList[id]) this.items[index].price = parseFloat(this.productsList[id]);
        },
    
        addItem() { this.items.push({ product_id: '', quantity: 1, price: 0 }); },
        removeItem(index) { if (this.items.length > 1) this.items.splice(index, 1); },
    
        get totalSale() {
            return this.items.reduce((sum, i) => sum + ((parseFloat(i.quantity) || 0) * (parseFloat(i.price) || 0)), 0);
        },
    
        get totalFormatted() {
            return this.totalSale.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }"
    class="min-h-screen lg:h-screen bg-white flex flex-col px-6 lg:px-12 py-8 lg:py-10 font-sans antialiased text-black overflow-x-hidden lg:overflow-hidden">

        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
            <div>
                <h1 class="text-3xl font-black tracking-tight italic">Vendas</h1>
                <p class="text-[10px] text-zinc-400 font-black uppercase tracking-[0.3em] mt-1">Histórico de Transações</p>
            </div>

            <div class="flex items-center gap-6 w-full md:w-auto">
                <div class="hidden sm:block text-right border-r border-zinc-100 pr-6">
                    <p class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Volume Total</p>
                    <p class="text-lg font-black leading-none italic">{{ $sales->count() }}</p>
                </div>
                <button @click="openCreate()"
                    class="flex-1 md:flex-none h-14 px-8 bg-black text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-zinc-800 transition-all shadow-xl shadow-zinc-200 active:scale-95">
                    Registrar Venda
                </button>
            </div>
        </header>

        <section class="hidden lg:block bg-white border border-zinc-100 rounded-[40px] shadow-sm overflow-hidden flex-1">
            <div class="overflow-y-auto h-full custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 bg-white border-b border-zinc-100 z-10">
                        <tr>
                            <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-zinc-400">Cliente</th>
                            <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-zinc-400">Data</th>
                            <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-zinc-400">Total</th>
                            <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-zinc-400">Status</th>
                            <th class="px-8 py-5 text-right text-[9px] font-black uppercase tracking-[0.2em] text-zinc-400">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50">
                        @forelse ($sales as $sale)
                            <tr class="hover:bg-zinc-50/30 transition-colors group">
                                <td class="px-8 py-5 font-black text-sm text-zinc-900">{{ $sale->customer->name }}</td>
                                <td class="px-8 py-5 text-xs text-zinc-500 font-bold italic">{{ dataFormatada($sale->sale_date) }}</td>
                                <td class="px-8 py-5 text-sm font-black italic">R$ {{ number_format($sale->total_amount, 2, ',', '.') }}</td>
                                <td class="px-8 py-5">
                                    <span @class([
                                        'px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest border',
                                        'bg-green-50 text-green-600 border-green-200' => $sale->status === 'concluida',
                                        'bg-yellow-50 text-yellow-600 border-yellow-200' => $sale->status === 'pendente',
                                        'bg-rose-50 text-rose-600 border-rose-100' => $sale->status === 'cancelada',
                                    ])>
                                        {{ $sale->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-end gap-6">
                                        <button @click="openEdit({{ $sale->load('items') }})"
                                            class="text-[9px] font-black uppercase tracking-widest text-zinc-400 hover:text-black transition-colors">Detalhes</button>
                                        
                                        <form action="{{ route('sales.destroy', $sale) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-[10px] font-black uppercase tracking-widest text-zinc-300 hover:text-rose-600 transition-colors mb-2">Excluir</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <p class="py-20 text-center text-zinc-400 font-bold">Nenhuma venda registrada</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="lg:hidden space-y-4 pb-20">
            @forelse ($sales as $sale)
                <div class="p-6 bg-white border border-zinc-100 rounded-[32px] shadow-sm flex flex-col gap-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-sm font-black text-zinc-900 leading-none italic">{{ $sale->customer->name }}</h3>
                            <p class="text-[9px] font-black text-zinc-400 uppercase tracking-widest mt-2">
                                {{ dataFormatada($sale->sale_date) }}
                            </p>
                        </div>
                        <p class="text-sm font-black text-zinc-900 italic">
                            R$ {{ number_format($sale->total_amount, 2, ',', '.') }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-zinc-50">
                        <span @class([
                            'px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest border',
                            'bg-green-50 text-green-600 border-green-200' => $sale->status === 'concluida',
                            'bg-yellow-50 text-yellow-600 border-yellow-200' => $sale->status === 'pendente',
                            'bg-rose-50 text-rose-600 border-rose-100' => $sale->status === 'cancelada',
                        ])>
                            {{ $sale->status }}
                        </span>
                        
                        <div class="flex gap-6">
                            <button @click="openEdit({{ $sale->load('items') }})" 
                                    class="text-[9px] font-black uppercase tracking-widest text-zinc-400">Detalhes</button>
                            
                            <form action="{{ route('sales.destroy', $sale) }}" method="POST" onsubmit="return confirm('Excluir venda?')">
                                @csrf @method('DELETE')
                                <button class="text-[9px] font-black uppercase tracking-widest text-rose-500/50">Excluir</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-20 text-center">
                    <p class="text-[10px] text-zinc-400 font-black uppercase tracking-widest">Sem vendas registradas</p>
                </div>
            @endforelse
        </section>

        <x-ui.sales-modal :products="$products" :customers="$customers" name="sale-modal" />

    </main>
</x-layouts.layout-dash>