
<x-layout>
    <main class="py-10">
      <section class="bg-white max-w-150 mx-auto mt-4 p-10 border-2">
        <h1 class="font-bold text-3xl">
          Adicionar Produto
        </h1>
  
        <p>
          Preencha as informações do produto
        </p>
  
        <form action="{{ route('products.store') }}" method="POST" class="flex flex-col ">
          @csrf
  
          <div class="flex flex-col gap-2 mb-4">
            <label for="name">Nome</label>
            <input type="text" name="name" placeholder="Ex: Camisa Polo G" class="bg-white border-2 p-2 @error('name') border-red-500 @enderror">
            @error('name')
              <p class="text-red-500 text-sm">
                {{ $message }}
              </p>
            @enderror
          </div>
  
          <div class="flex flex-col gap-2 mb-4">
            <label for="price">Preço</label>
            <input type='number' name="price" placeholder="234,55" class="bg-white border-2 p-2 @error('price') border-red-500 @enderror">
            @error('price')
              <p class="text-red-500 text-sm">
                {{ $message }}
              </p>
            @enderror
          </div>
  
          <button type="submit" class="bg-white p-2 border-2">Adicionar Produto</button>
        </form>
      </section>
    </main>
  </x-layout>
  