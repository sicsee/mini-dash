<x-layouts.layout-dash>
    <main x-data="{
    activeModal: null,
    currentCustomer: {
        id: null,
        name: '',
        email: ''
        phone: ''
    }
}" class="container-dash">

        <!-- Cabeçalho -->
        <header class="flex flex-col items-center w-full max-w-7xl mt-10 gap-10">
            <x-ui.card>
                <x-ui.card-title >
                    Total de clientes registrados:  {{ auth()->user()->total_customers}}
                </x-ui.card-title> 
                <x-lucide-user  class="w-7 h-7"/>
            </x-ui.card>
            <div class="flex justify-between w-full">
                <h1 class="title-dash">Clientes</h1>

                <!-- Modal (Alpine) -->
                <button @click="activeModal = 'customer-create'" class="btn btn-lg btn-default">
                    Cadastrar Cliente
                </button>
            </div>
        </header>


        <section class="bg-white rounded-xl shadow-sm border border-gray-200 table-container w-full">
            <table class="min-w-full border-collapse">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-r border-gray-200">
                            Nome
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-r border-gray-200">
                            Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-r border-gray-200">
                            Telefone
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($customers as $c)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-gray-800 border-r border-gray-200">
                                {{ $c->name }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-800 border-r border-gray-200">
                                {{ $c->email }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-800 border-r border-gray-200">
                                {{ $c->phone_formatted }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button @click="
                                        currentCustomer = {
                                            id: {{ $c->id }},
                                            name: '{{ addslashes($c->name) }}',
                                            email: {{ $c->email }},
                                            phone: {{ $c->phone }}
                                        };
                                        activeModal = 'customer-edit';
                                    " class="btn btn-sm font-bold bg-blue-600 text-white hover:bg-blue-700">
                                        Editar
                                    </button>

                                    <form action="{{ route('products.destroy', $c) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm font-bold bg-red-600 text-white hover:bg-red-700">
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-6 text-center text-sm text-gray-500">
                                Nenhum cliente cadastrado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <x-ui.modal name="customer-create" title="Cadastrar Cliente">
            <form class="space-y-4" action="{{ route('customers.store') }}" method="POST">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-600">
                        Nome do Cliente
                    </label>
                    <input type="text" name="name" placeholder="Fulano"
                        class="w-full mt-1 px-3 py-2 border rounded-md">
                    @error('name')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-600">
                        Email
                    </label>
                    <input type="text" name="email" placeholder="fulano@email.com"
                        class="w-full mt-1 px-3 py-2 border rounded-md">
                    @error('email')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-600">
                        Telefone
                    </label>
                    <input type="number" name="phone" placeholder="+55 012 93456-7890"
                        class="w-full mt-1 px-3 py-2 border rounded-md">
                    @error('phone')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="activeModal = null" class="px-4 py-2 text-sm bg-gray-200 rounded-md">
                        Cancelar
                    </button>

                    <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-md">
                        Salvar
                    </button>
                </div>
            </form>
        </x-ui.modal>

        {{-- <x-ui.modal name="customer-edit" title="Editar Produto">
            <form method="POST" :action="`/dashboard/customers/${currentProduct.id}`" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-600">
                        Nome do produto
                    </label>
                    <input type="text" name="name" x-model="currentProduct.name"
                        class="w-full mt-1 px-3 py-2 border rounded-md">
                    @error('name')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-gray-600">
                        Preço
                    </label>
                    <input type="number" name="price" x-model="currentProduct.price"
                        class="w-full mt-1 px-3 py-2 border rounded-md">
                    @error('price')
                        <p class="text-red-500 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="activeModal = null" class="px-4 py-2 text-sm bg-gray-200 rounded-md">
                        Cancelar
                    </button>

                    <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-md">
                        Salvar
                    </button>
                </div>
            </form>
        </x-ui.modal> --}}

    </main>
</x-layouts.layout-dash>