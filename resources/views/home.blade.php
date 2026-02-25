<x-layout>
  <main class="text-escuro flex justify-center items-center flex-col h-screen bg-center bg-no-repeat bg-cover" style="background-image: url('{{ asset('images/bg-land.png') }}')">
    <x-header />

    <section class="flex justify-center flex-col text-center">
      <div>
        <h2 class="text-4xl md:text-6xl font-bold mb-6 text-white font-bodoni">
          Mini Dash
        </h2>
        <p class="text-lg text-zinc-100 mb-8 max-w-xl mx-auto">
          Um sistema simples e eficaz de gerenciamento de vendas e produtos
          com um dashboard completo.
        </p>
      </div>
      <div>
        @guest
          <a href="{{ route('auth.register') }}" class="btn btn-secondary btn-md">Cadastrar</a>
        @endguest
        @auth
          <a href="{{ route('site.dashboard') }}" class="btn btn-secondary btn-md">Dashboard</a>
        @endauth
        
        
      </div>
    </section>
  </main>
</x-layout>
