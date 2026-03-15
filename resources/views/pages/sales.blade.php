<x-layouts.layout-dash>
    <main x-data="{
        activeModal: null,
        formData: { id: null, customer_id: '', sale_date: '{{ now()->format('Y-m-d') }}', status: 'concluida', notes: '' },
        items: [],
        productsStock: {{ $products->mapWithKeys(fn($p) => [$p->id => $p->stock->quantity]) }},
        productsList: {{ $products->mapWithKeys(fn($p) => [$p->id => $p->price]) }},
    
        openCreate() {
            this.formData = { id: null, customer_id: '', sale_date: '{{ now()->format('Y-m-d') }}', status: 'concluida', notes: '' };
            this.items = [{ product_id: '', quantity: 1, price: 0 }];
            this.activeModal = 'sale-modal';
        },
    
        openEdit(sale) {
            let formattedDate = sale.sale_date ? sale.sale_date.split('T')[0].split(' ')[0] : '';
            this.formData = {
                id: sale.id,
                customer_id: sale.customer_id,
                sale_date: formattedDate,
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
            const productId = this.items[index].product_id;
            if (productId && this.productsList[productId]) {
                this.items[index].price = parseFloat(this.productsList[productId]);
            }
        },
    
        addItem() { this.items.push({ product_id: '', quantity: 1, price: 0 }); },
        removeItem(index) { if (this.items.length > 1) this.items.splice(index, 1); },
    
        hasEnoughStock(index) {
            const item = this.items[index];
            if (!item.product_id) return true;
            return item.quantity <= this.productsStock[item.product_id];
        },
    
        get totalSale() {
            return this.items.reduce((sum, item) => {
                const qty = parseFloat(item.quantity) || 0;
                const price = parseFloat(item.price) || 0;
                return sum + (qty * price);
            }, 0).toFixed(2);
        },
    
        get totalFormatted() {
            return parseFloat(this.totalSale).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    }"
        class="min-h-screen bg-white flex flex-col px-6 lg:px-12 py-8 lg:py-10 font-sans antialiased text-black">

        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 lg:mb-12 gap-6">
            <div>
                <h1 class="text-3xl font-black tracking-tight">Vendas</h1>
                <p class="text-xs text-zinc-400 font-bold uppercase tracking-widest mt-1">Histórico de Transações</p>
            </div>

            <div class="flex items-center justify-between w-full md:w-auto gap-6">
                <div class="text-left md:text-right border-r border-zinc-100 pr-6">
                    <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Volume Total</p>
                    <p class="text-lg font-black">{{ auth()->user()->sales()->count() }}</p>
                </div>
                <button @click="openCreate()"
                    class="h-12 px-6 lg:px-8 bg-black text-white rounded-xl text-[10px] lg:text-xs font-black uppercase tracking-widest hover:bg-zinc-800 transition-all shadow-lg shadow-zinc-200">
                    Registrar Venda
                </button>
            </div>
        </header>

        <section class="hidden lg:block bg-white border border-zinc-100 rounded-3xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-zinc-50 border-b border-zinc-100">
                        <tr>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-zinc-400">Cliente
                            </th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-zinc-400">Data
                            </th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-zinc-400">Total
                            </th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-zinc-400">Status
                            </th>
                            <th
                                class="px-8 py-5 text-right text-[10px] font-black uppercase tracking-widest text-zinc-400">
                                Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50">
                        @forelse ($sales as $sale)
                            <tr class="hover:bg-zinc-50/50 transition-colors group">
                                <td class="px-8 py-5 font-bold text-sm text-zinc-900">{{ $sale->customer->name }}</td>
                                <td class="px-8 py-5 text-sm text-zinc-600">{{ dataFormatada($sale->sale_date) }}</td>
                                <td class="px-8 py-5 text-sm font-black text-zinc-900">R$
                                    {{ number_format($sale->total_amount, 2, ',', '.') }}</td>
                                <td class="px-8 py-5">
                                    <span @class([
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-tighter border',
                                        'bg-green-50 text-green-600 border-green-600' =>
                                            $sale->status === 'concluida',
                                        'bg-yellow-50 text-yellow-600 border-yellow-600' =>
                                            $sale->status === 'pendente',
                                        'bg-rose-50 text-rose-600 border-rose-100' => $sale->status === 'cancelada',
                                    ])>
                                        {{ $sale->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex justify-end gap-4 items-center">
                                        <button @click="openEdit({{ $sale->load('items') }})"
                                            class="text-[10px] font-black uppercase text-zinc-400 hover:text-black transition-colors">Detalhes</button>
                                        <form class="mb-1" action="{{ route('sales.destroy', $sale) }}"
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

        <section class="lg:hidden space-y-4 pb-24">
            @forelse ($sales as $sale)
                <div class="p-6 bg-white border border-zinc-100 rounded-[28px] shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1">
                                {{ dataFormatada($sale->sale_date) }}</p>
                            <h3 class="text-base font-black text-zinc-900 leading-tight">{{ $sale->customer->name }}
                            </h3>
                        </div>
                        <span @class([
                            'px-2 py-0.5 rounded-full text-[9px] font-black uppercase border',
                            'bg-green-50 text-green-600 border-green-600' =>
                                $sale->status === 'concluida',
                            'bg-yellow-50 text-yellow-600 border-yellow-600' =>
                                $sale->status === 'pendente',
                        ])>
                            {{ $sale->status }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-zinc-50">
                        <p class="text-lg font-black text-zinc-950">R$
                            {{ number_format($sale->total_amount, 2, ',', '.') }}</p>

                        <div class="flex gap-2 items-center">
                            <button @click="openEdit({{ $sale->load('items') }})"
                                class="h-10 px-4 bg-zinc-50 rounded-xl text-[10px] font-black uppercase hover:bg-zinc-100 transition-all">
                                Detalhes
                            </button>
                            <form action="{{ route('sales.destroy', $sale) }}" method="POST">
                                @csrf @method('DELETE')
                                <button
                                    class="h-10 w-10 flex items-center justify-center bg-rose-50 text-rose-600 rounded-xl">
                                    <x-lucide-trash-2 class="w-4 h-4" />
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-20 text-center text-zinc-300 font-medium">Nenhuma venda.</div>
            @endforelse
        </section>

        <x-ui.sales-modal :products="$products" :customers="$customers" name="sale-modal" />
    </main>
</x-layouts.layout-dash>
