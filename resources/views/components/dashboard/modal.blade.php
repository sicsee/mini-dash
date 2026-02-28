<div x-data="{ open: false }">
    <button
        @click="open = true"
        class="btn btn-lg btn-default">
        Cadastrar Produto
    </button>

    <!-- Overlay -->
    <div
        x-show="open"
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-100/50"
        style="display: none;"
        @click.self="open = false"
        @keydown.escape.window="open = false">

        <!-- Modal -->
        <section
            x-transition.scale
            class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">

            <!-- Header -->
            <header class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-800">
                    Cadastrar Produto
                </h2>

                <button
                    @click="open = false"
                    class="text-gray-400 hover:text-gray-600 text-xl font-bold">
                    &times;
                </button>
            </header>

            <!-- Form -->
            <form action="{{ route('products.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-600">
                        Nome do produto
                    </label>
                    <input
                        type="text"
                        name="name"
                        placeholder="Ex: Camisa Polo G"
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600">
                        Preço
                    </label>
                    <input
                        type="number"
                        name="price"
                        placeholder="135,00"
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Footer -->
                <footer class="flex justify-end gap-2 pt-4">
                    <button
                        type="button"
                        @click="open = false"
                        class="px-4 py-2 text-sm rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="px-4 py-2 text-sm rounded-md bg-blue-600 text-white hover:bg-blue-700">
                        Salvar
                    </button>
                </footer>
            </form>
        </section>
    </div>
</div>