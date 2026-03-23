@props(['products', 'customers', 'name'])

<section x-show="activeModal === '{{ $name }}'" x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" style="display: none;"
    @keydown.escape.window="activeModal = null">

    <div @click="activeModal = null" class="absolute inset-0"></div>


    <div x-show="activeModal === '{{ $name }}'" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-5xl overflow-hidden mx-4">
        <form :action="formData.id ? `/dashboard/sales/${formData.id}` : '{{ route('sales.store') }}'" method="POST"
            class="flex flex-col">
            @csrf
            <template x-if="formData.id">
                <input type="hidden" name="_method" value="PUT">
            </template>

            <div class="px-8 py-6 border-b border-zinc-700 flex items-center justify-between bg-zinc-50/50">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-zinc-900 rounded-lg flex items-center justify-center text-white shadow-sm">
                        <x-lucide-dollar-sign class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-zinc-900 leading-none"
                            x-text="formData.id ? 'Editar Venda #' + formData.id : 'Nova Venda'"></h2>
                        <p class="text-xs text-zinc-500 mt-1 uppercase tracking-wider font-bold">
                            Painel de Transação
                        </p>
                    </div>
                </div>
                <button type="button" @click="activeModal = null"
                    class="text-zinc-400 hover:text-zinc-600 transition-colors">
                    <x-lucide-x class="w-6 h-6" />
                </button>
            </div>

            <div class="p-8 space-y-10 max-h-[70vh] overflow-y-auto custom-scrollbar">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-zinc-400 uppercase tracking-widest">Cliente
                            Responsável</label>
                        <select x-model="formData.customer_id" name="customer_id" required
                            class="w-full h-11 px-4 rounded-xl border border-zinc-700 bg-white focus:border-zinc-900 focus:ring-0 transition-all text-sm outline-none">
                            <option value="">Selecione...</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-zinc-400 uppercase tracking-widest">Data de
                            Emissão</label>
                        <input x-model="formData.sale_date" type="date" name="sale_date"
                            class="w-full h-11 px-4 rounded-xl border border-zinc-700 bg-white focus:border-zinc-900 transition-all text-sm outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-zinc-400 uppercase tracking-widest">Status</label>
                        <select x-model="formData.status" name="status"
                            class="w-full h-11 px-4 rounded-xl border border-zinc-700 bg-white focus:border-zinc-900 transition-all text-sm font-semibold outline-none">
                            <option value="concluida">Concluída</option>
                            <option value="pendente">Pendente</option>
                            <option value="cancelada">Cancelada</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between pb-2">
                        <h3 class="text-[11px] font-bold text-zinc-900 uppercase tracking-[0.2em]">Listagem de Produtos
                        </h3>
                        <button type="button" @click="addItem()"
                            class="px-4 py-1.5 text-[10px] font-black border border-zinc-700 text-zinc-600 rounded-full hover:bg-zinc-900 hover:text-white transition-all uppercase">
                            + Adicionar Item
                        </button>
                    </div>

                    <div
                        class="hidden md:flex items-center px-4 py-2 bg-zinc-50 border border-zinc-100 rounded-t-lg text-[10px] font-bold text-zinc-400 uppercase">
                        <div class="flex-1">Produto / Serviço</div>
                        <div class="w-32 text-center">Quantidade</div>
                        <div class="w-40 text-center">Preço Unitário</div>
                        <div class="w-40 text-right">Subtotal</div>
                        <div class="w-10"></div>
                    </div>

                    <div class="border border-zinc-100 rounded-b-xl divide-y divide-zinc-100">
                        <template x-for="(item, index) in items" :key="index">
                            <div
                                class="flex flex-col md:flex-row items-center gap-4 p-4 bg-white hover:bg-zinc-50/30 transition-colors group">
                                <div class="w-full md:flex-1">
                                    <select x-model="item.product_id" @change="updatePrice(index)"
                                        :name="'items[' + index + '][product_id]'"
                                        class="w-full h-10 px-3 border border-zinc-700 bg-zinc-100/50 rounded-lg text-sm focus:bg-white focus:border-zinc-200 transition-all outline-none">
                                        <option value="">Buscar produto...</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-full md:w-32">
                                    <input type="number" min="1" x-model.number="item.quantity"
                                        :name="'items[' + index + '][quantity]'"
                                        :class="!hasEnoughStock(index) ? 'border-red-500 bg-red-50' :
                                            'border border-zinc-700 bg-zinc-100/50'"
                                        class="w-full h-10 text-center text-sm font-bold border rounded-lg focus:bg-white transition-all outline-none">

                                    <template x-if="item.product_id && !hasEnoughStock(index)">
                                        <span
                                            class="text-[9px] text-red-600 font-bold uppercase block mt-1 text-center leading-tight">
                                            Estoque insuficiente<br>(Disp: <span
                                                x-text="productsStock[item.product_id]"></span>)
                                        </span>
                                    </template>
                                </div>
                                <div class="w-full md:w-40">
                                    <div class="relative">
                                        <span
                                            class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-zinc-400">R$</span>
                                        <input type="number" step="0.01" x-model.number="item.price"
                                            :name="'items[' + index + '][price]'"
                                            class="w-full h-10 pl-8 pr-3 bg-zinc-100/50 rounded-lg text-sm border border-zinc-700 focus:bg-white focus:border-zinc-200 transition-all text-right font-medium outline-none">
                                    </div>
                                </div>
                                <div class="w-full md:w-40 text-right">
                                    <span class="text-sm font-bold text-zinc-900"
                                        x-text="'R$ ' + (item.quantity * item.price).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                                </div>
                                <div class="w-full md:w-10 flex justify-end">
                                    <button type="button" @click="removeItem(index)"
                                        class="p-2 text-zinc-300 hover:text-red-500 transition-colors">
                                        <x-lucide-trash-2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-zinc-400 uppercase tracking-widest">Notas
                        Adicionais</label>
                    <textarea x-model="formData.notes" name="notes" rows="2"
                        class="w-full p-4 rounded-xl border border-zinc-700 bg-zinc-50/50 focus:bg-white transition-all text-sm resize-none outline-none"
                        placeholder="Observações da venda..."></textarea>
                </div>
            </div>

            <div
                class="px-8 py-6 bg-zinc-900 flex flex-col md:flex-row items-center justify-between gap-6 rounded-b-2xl">
                <div class="flex items-center gap-6">
                    <div class="text-zinc-500">
                        <span class="text-[10px] font-bold uppercase tracking-tighter block">Itens</span>
                        <span class="text-xl font-bold text-white" x-text="items.length"></span>
                    </div>
                    <div class="w-px h-8 bg-zinc-800"></div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-tighter text-zinc-500 block">Total
                            Geral</span>
                        <span class="text-3xl font-black text-white" x-text="'R$ ' + totalFormatted"></span>
                    </div>
                </div>

                <div class="flex items-center gap-4 w-full md:w-auto">
                    <button type="button" @click="activeModal = null"
                        class="flex-1 md:flex-none px-6 py-3 text-xs font-bold text-zinc-400 hover:text-white transition-colors uppercase">
                        Descartar
                    </button>
                    <button type="submit"
                        class="flex-1 md:flex-none px-10 py-3 bg-white text-zinc-900 rounded-xl font-black text-xs hover:bg-zinc-200 transition-all uppercase tracking-widest shadow-xl">
                        <span x-text="formData.id ? 'Salvar Alterações' : 'Finalizar Venda'"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>
