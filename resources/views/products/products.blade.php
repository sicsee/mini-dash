<x-layouts.layout-dash>
  <main class="container-dash">
      
      <!-- Cabeçalho -->
      <header class="flex items-center justify-between w-full max-w-7xl mt-10">
          <h1 class="title-dash">Produtos</h1>

          <!-- Modal (Alpine) -->
          <x-dashboard.modal />
      </header>

      
      <section class="bg-white rounded-xl shadow-sm border border-gray-200 table-container w-full">
          <table class="min-w-full border-collapse">
              <thead class="bg-gray-50">
                  <tr>
                      <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-r border-gray-200">
                          Produto
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-r border-gray-200">
                          Preço
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

                          <td class="px-6 py-4 text-right">
                              <div class="flex justify-end gap-2">
                                  <x-dashboard.modal-edit :p="$p"/>

                                  <form action="{{ route('products.destroy', $p) }}" method="POST">
                                      @csrf
                                      @method('DELETE')
                                      <button class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md bg-red-600 text-white hover:bg-red-700">
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

  </main>
</x-layouts.layout-dash>