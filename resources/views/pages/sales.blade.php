<x-layouts.layout-dash>

    <main 
        x-data="{
            activeModal: null,
            formData: { id: null, customer_id: '', sale_date: '', status: 'concluida', notes: '' },
            items: [],

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
                    quantity: item.quantity,
                    price: item.price
                }));
                this.activeModal = 'sale-modal';
            },

            addItem() {
                this.items.push({ product_id: '', quantity: 1, price: 0 });
            },

            removeItem(index) {
                if(this.items.length > 1) this.items.splice(index, 1);
            },

            get totalSale() {
                const total = this.items.reduce((sum, item) => sum + (item.quantity * item.price), 0);
                return total.toFixed(2);
            }
        }"
        class="container-dash"
    >

        <header class="flex flex-col items-center w-full max-w-7xl mt-10 gap-10">
            <x-ui.card>
                <x-ui.card-title>
                    Total de vendas: {{ auth()->user()->sales()->count() }}
                </x-ui.card-title>
                <x-lucide-shopping-cart class="w-7 h-7"/>
            </x-ui.card>

            <div class="flex justify-between w-full">
                <h1 class="title-dash">Vendas</h1>
                <button @click="openCreate()" class="btn btn-lg btn-default">
                    Registrar Venda
                </button>
            </div>
        </header>

        <section class="bg-white rounded-xl shadow-sm border border-gray-200 table-container w-full mt-6">
            <table class="min-w-full border-collapse">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-r border-gray-200">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-r border-gray-200">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-r border-gray-200">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-r border-gray-200">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($sales as $sale)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-medium text-gray-800 border-r border-gray-200">{{ $sale->customer->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 border-r border-gray-200">{{ $sale->sale_date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 border-r border-gray-200">R$ {{ number_format($sale->total_amount, 2, ',', '.') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 border-r border-gray-200">{{ ucfirst($sale->status) }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button @click="openEdit({{ $sale->load('items') }})" class="btn btn-sm font-bold bg-blue-600 text-white hover:bg-blue-700">
                                    Editar
                                </button>
                                <form action="{{ route('sales.destroy', $sale) }}" method="POST" onsubmit="return confirm('Deseja excluir?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm font-bold bg-red-600 text-white hover:bg-red-700">
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-6 text-center text-sm text-gray-500">Nenhuma venda registrada</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <x-ui.modal name="sale-modal" title="Formulário de Venda">
            <div class="mb-4">
                <h2 class="text-lg font-semibold text-gray-800" x-text="formData.id ? 'Editar Venda #' + formData.id : 'Registrar Nova Venda'"></h2>
            </div>

            <form 
                :action="formData.id ? `/dashboard/sales/${formData.id}` : '{{ route('sales.store') }}'" 
                method="POST" 
                class="space-y-6"
            >
                @csrf
                <template x-if="formData.id">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Cliente</label>
                        <select x-model="formData.customer_id" name="customer_id" required class="w-full mt-1 px-3 py-2 border rounded-md text-sm bg-white">
                            <option value="">Selecione um cliente</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Data da venda</label>
                        <input type="date" x-model="formData.sale_date" name="sale_date" class="w-full mt-1 px-3 py-2 border rounded-md text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600">Status</label>
                        <select x-model="formData.status" name="status" class="w-full mt-1 px-3 py-2 border rounded-md text-sm bg-white">
                            <option value="concluida">Concluída</option>
                            <option value="pendente">Pendente</option>
                            <option value="cancelada">Cancelada</option>
                        </select>
                    </div>
                </div>

                <section class="space-y-3">
                    <div class="flex items-center justify-between border-b pb-2">
                        <h3 class="text-xs font-bold text-gray-500 uppercase">Itens</h3>
                        <button type="button" @click="addItem()" class="text-xs font-bold text-blue-600 hover:underline">+ ADICIONAR ITEM</button>
                    </div>

                    <div class="space-y-3 max-h-[250px] overflow-y-auto">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="grid grid-cols-12 gap-2 items-end p-3 bg-gray-50 rounded-lg border border-gray-100">
                                <div class="col-span-12 md:col-span-5">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase">Produto</label>
                                    <select x-model="item.product_id" :name="`items[${index}][product_id]`" required class="w-full mt-1 px-2 py-1.5 border rounded text-xs bg-white">
                                        <option value="">Selecione</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-4 md:col-span-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase">Qtd</label>
                                    <input type="number" x-model.number="item.quantity" :name="`items[${index}][quantity]`" min="1" class="w-full mt-1 px-2 py-1.5 border rounded text-xs text-center">
                                </div>
                                <div class="col-span-5 md:col-span-3">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase">Preço (R$)</label>
                                    <input type="number" step="0.01" x-model.number="item.price" :name="`items[${index}][price]`" class="w-full mt-1 px-2 py-1.5 border rounded text-xs text-right">
                                </div>
                                <div class="col-span-3 md:col-span-2 flex justify-end">
                                    <button type="button" @click="removeItem(index)" class="p-2 text-red-500 hover:bg-red-50 rounded">
                                        <x-lucide-trash class="w-4 h-4"/>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </section>

                <div class="flex justify-between items-center pt-4 border-t">
                    <div class="text-sm">
                        <span class="text-gray-500">Total:</span>
                        <span class="font-bold text-gray-800 ml-1">R$ <span x-text="totalSale.replace('.', ',')"></span></span>
                    </div>

                    <div class="flex gap-2">
                        <button type="button" @click="activeModal = null" class="px-4 py-2 text-sm bg-gray-200 rounded-md">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            Salvar Venda
                        </button>
                    </div>
                </div>
            </form>
        </x-ui.modal>

    </main>
</x-layouts.layout-dash>