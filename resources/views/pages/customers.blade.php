<x-layouts.layout-dash>
    <main x-data="{
        activeModal: null,
        currentCustomer: { id: null, name: '', email: '', phone: '' }
    }"
        class="min-h-screen lg:h-screen bg-white flex flex-col px-6 lg:px-12 py-8 lg:py-10 font-sans antialiased text-black overflow-x-hidden lg:overflow-hidden">

        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 lg:mb-12 gap-6">
            <div>
                <h1 class="text-3xl font-black tracking-tight">Clientes</h1>
                <p class="text-[10px] text-zinc-400 font-black uppercase tracking-[0.2em] mt-1">Gestão de Relacionamento
                </p>
            </div>

            <div class="flex items-center justify-between w-full md:w-auto gap-6">
                <div class="text-left md:text-right border-r border-zinc-100 pr-6">
                    <p class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Base Ativa</p>
                    <p class="text-lg font-black leading-none">{{ auth()->user()->total_customers }}</p>
                </div>
                <button @click="activeModal = 'customer-create'"
                    class="h-12 px-6 lg:px-8 bg-black text-white rounded-xl text-[10px] lg:text-xs font-black uppercase tracking-widest hover:bg-zinc-800 transition-all shadow-xl shadow-zinc-200">
                    + <span class="hidden sm:inline">Cadastrar Cliente</span><span class="sm:hidden">Novo</span>
                </button>
            </div>
        </header>

        <section class="hidden lg:block bg-white border border-zinc-100 rounded-[32px] shadow-sm overflow-hidden">
            <div class="overflow-y-auto max-h-[calc(100vh-320px)] custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 bg-zinc-50 border-b border-zinc-100 z-10">
                        <tr>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-zinc-400">
                                Identificação</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-zinc-400">E-mail
                            </th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-zinc-400">
                                Telefone</th>
                            <th
                                class="px-8 py-5 text-right text-[10px] font-black uppercase tracking-widest text-zinc-400">
                                Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50">
                        @forelse ($customers as $c)
                            <tr class="hover:bg-zinc-50/50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-9 h-9 rounded-2xl bg-zinc-950 flex items-center justify-center text-[11px] font-black text-white shadow-lg shadow-zinc-200">
                                            {{ substr($c->name, 0, 2) }}
                                        </div>
                                        <span
                                            class="text-sm font-black text-zinc-900 tracking-tight">{{ $c->name }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-sm text-zinc-500 font-bold italic">{{ $c->email }}</td>
                                <td class="px-8 py-5 text-sm text-zinc-900 font-black tracking-tighter">
                                    {{ $c->phone_formatted }}</td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex justify-end gap-6 items-center">
                                        <button
                                            @click="currentCustomer = { id: {{ $c->id }}, name: '{{ addslashes($c->name) }}', email: '{{ $c->email }}', phone: '{{ $c->phone }}' }; activeModal = 'customer-edit';"
                                            class="text-[10px] font-black uppercase text-zinc-400 hover:text-black transition-colors">Editar</button>
                                        <form class="mb-1" action="{{ route('customers.destroy', $c) }}"
                                            method="POST">
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
            @forelse ($customers as $c)
                <div class="p-6 bg-white border border-zinc-100 rounded-[28px] shadow-sm flex flex-col gap-5">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-[18px] bg-zinc-950 flex items-center justify-center text-xs font-black text-white shadow-xl shadow-zinc-200">
                            {{ substr($c->name, 0, 2) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-black text-zinc-900 leading-tight tracking-tight">
                                {{ $c->name }}</h3>
                            <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mt-0.5">Cliente
                                Ativo</p>
                        </div>
                    </div>

                    <div class="space-y-2 pt-4 border-t border-zinc-50">
                        <div class="flex items-center gap-3">
                            <x-lucide-mail class="w-3 h-3 text-zinc-300" />
                            <span class="text-xs font-bold text-zinc-500 italic">{{ $c->email }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <x-lucide-phone class="w-3 h-3 text-zinc-300" />
                            <span
                                class="text-xs font-black text-zinc-900 tracking-tighter">{{ $c->phone_formatted }}</span>
                        </div>
                    </div>

                    <div class="flex gap-3 items-center">
                        <button
                            @click="currentCustomer = { id: {{ $c->id }}, name: '{{ addslashes($c->name) }}', email: '{{ $c->email }}', phone: '{{ $c->phone }}' }; activeModal = 'customer-edit';"
                            class="flex-1 bg-zinc-50 rounded-xl text-[10px] font-black uppercase tracking-widest text-zinc-600">
                            Editar
                        </button>
                        <form action="{{ route('customers.destroy', $c) }}" method="POST" class="shrink">
                            @csrf @method('DELETE')
                            <button
                                class="w-11 h-11 flex items-center justify-center bg-rose-50 text-rose-600 rounded-xl">
                                <x-lucide-trash-2 class="w-4 h-4" />
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="py-20 text-center text-zinc-300 font-black uppercase text-[10px] tracking-widest">Nenhum
                    cliente na base</div>
            @endforelse
        </section>

        <template x-if="activeModal === 'customer-create' || activeModal === 'customer-edit'">
            <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/40 backdrop-blur-sm p-0 sm:p-4"
                @click.self="activeModal = null">

                <div class="bg-white w-full max-w-lg rounded-t-[32px] sm:rounded-[40px] shadow-2xl overflow-hidden border border-zinc-100"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-4">

                    <div class="px-8 py-6 border-b border-zinc-50 flex justify-between items-center bg-zinc-50/50">
                        <h2 class="text-xl font-black tracking-tight"
                            x-text="activeModal === 'customer-create' ? 'Novo Cliente' : 'Editar Dados'"></h2>
                        <button @click="activeModal = null"
                            class="w-8 h-8 flex items-center justify-center bg-white rounded-full text-zinc-300 hover:text-black shadow-sm">
                            <x-lucide-x class="w-4 h-4" />
                        </button>
                    </div>

                    <form class="p-8 space-y-5"
                        :action="activeModal === 'customer-create' ? '{{ route('customers.store') }}' :
                            `/dashboard/customers/${currentCustomer.id}`"
                        method="POST">
                        @csrf
                        <template x-if="activeModal === 'customer-edit'">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-zinc-400 ml-1">Nome
                                Completo</label>
                            <input type="text" name="name" x-model="currentCustomer.name" required
                                class="w-full h-12 px-5 rounded-2xl border border-zinc-100 bg-zinc-50 focus:bg-white focus:border-black transition-all text-sm font-black outline-none shadow-sm">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-zinc-400 ml-1">E-mail
                                Principal</label>
                            <input type="email" name="email" x-model="currentCustomer.email" required
                                class="w-full h-12 px-5 rounded-2xl border border-zinc-100 bg-zinc-50 focus:bg-white focus:border-black transition-all text-sm font-black outline-none shadow-sm">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-zinc-400 ml-1">Telefone
                                / WhatsApp</label>
                            <input type="text" name="phone" x-model="currentCustomer.phone"
                                class="w-full h-12 px-5 rounded-2xl border border-zinc-100 bg-zinc-50 focus:bg-white focus:border-black transition-all text-sm font-black outline-none shadow-sm">
                        </div>

                        <div class="pt-6 flex gap-3">
                            <button type="button" @click="activeModal = null"
                                class="flex-1 h-14 text-[10px] font-black uppercase tracking-widest text-zinc-400">Cancelar</button>
                            <button type="submit"
                                class="flex-1 h-14 bg-black text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-zinc-800 shadow-xl transition-all">Salvar
                                Registro</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </main>
</x-layouts.layout-dash>
