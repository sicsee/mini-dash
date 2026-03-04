<x-layouts.layout-dash>
    <main x-data="{
    activeModal: null,
    currentProduct: {
        id: null,
        name: '',
        price: ''
    }
}" class="container-dash">

        <!-- Cabeçalho -->
        <header class="flex items-center justify-between w-full max-w-7xl mt-10">
            <h1 class="title-dash">Produtos</h1>

            <!-- Modal (Alpine) -->
            <button @click="activeModal = 'product-create'" class="btn btn-lg btn-default">
                Cadastrar Produto
            </button>
        </header>


        <section class="bg-white rounded-xl shadow-sm border border-gray-200 table-container w-full">
            <table class="min-w-full border-collapse">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-r border-gray-200">
                            Produto
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-r border-gray-200">
                            Preço
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-r border-gray-200">
                            Quantidade
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($products as $p)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-gray-800 border-r border-gray-200">
                                {{ $p->name }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-800 border-r border-gray-200">
                                R$ {{ number_format($p->price, 2, ',', '.') }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-800 border-r border-gray-200">
                                {{ $p->stock->quantity }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button @click="
                                        currentProduct = {
                                            id: {{ $p->id }},
                                            name: '{{ addslashes($p->name) }}',
                                            price: '{{ $p->price }}'
                                        };
                                        activeModal = 'product-edit';
                                    " class="btn btn-sm font-bold bg-blue-600 text-white hover:bg-blue-700">
                                        Editar
                                    </button>

                                    <form action="{{ route('products.destroy', $p) }}" method="POST">
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
                                Nenhum produto cadastrado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <x-ui.modal name="product-create" title="Cadastrar Produto">
            <form class="space-y-4" action="{{ route('products.store') }}" method="POST">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-600">
                        Nome do produto
                    </label>
                    <input type="text" name="name" placeholder="Ex: Camisa Polo G"
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
                    <input type="number" name="price" placeholder="Ex: 120"
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
        </x-ui.modal>

        <x-ui.modal name="product-edit" title="Editar Produto">
            <form method="POST" :action="`/dashboard/products/${currentProduct.id}`" class="space-y-4">
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
                    <input type="number" name="price" x-model="currentProduct.name"
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
        </x-ui.modal>

    </main>
</x-layouts.layout-dash>