<aside
    class="flex flex-col max-w-14 h-full border-r-2 border-zinc-200 shadow-lg shadow-zinc-200 w-full absolute left-0 top-0 p-2 pt-5">
    <a class="flex items-center p-1 bg-black rounded-full hover:scale-105 hover:shadow-lg hover:shadow-zinc-500 transition-all ease-linear duration-300"
        href="{{ route('site.index') }}">
        <x-lucide-globe class="text-white " />
    </a>
    <nav class="mt-5">
        <a href="{{ route('site.dashboard') }}" class="sidebar-item">
            <x-lucide-home class="w-7 h-7 {{ Route::is('site.dashboard') ? 'text-black' : 'text-zinc-400 hover:text-black cursor-pointer transition-linear duration-300' }}" />
            <span class="sidebar-tooltip">Dashboard</span>
        </a>

        <a href="{{ route('stocks.index') }}" class="sidebar-item">
            <x-lucide-archive class="w-7 h-7 {{ Route::is('stocks.index') ? 'text-black' : 'text-zinc-400 hover:text-black cursor-pointer transition-linear duration-300' }}" />
            <span class="sidebar-tooltip">Estoque</span>
        </a>

        <a href="{{ route('products.index') }}" class="sidebar-item">
            <x-lucide-package class="w-7 h-7 {{ Route::is('products.index') ? 'text-black' : 'text-zinc-400 hover:text-black cursor-pointer transition-linear duration-300' }}" />
            <span class=" sidebar-tooltip">Produtos</span>
        </a>

        <a href="{{ route('customers.index') }}" class="sidebar-item">
            <x-lucide-user class="w-7 h-7 text-zinc-400 hover:text-black cursor-pointer transition-linear duration-300" />
            <span class="sidebar-tooltip">Clientes</span>
        </a>

        <a href="#" class="sidebar-item">
            <x-lucide-dollar-sign class="w-7 h-7 text-zinc-400 hover:text-black cursor-pointer transition-linear duration-300" />

            <span class="sidebar-tooltip">Vendas</span>
        </a>
    </nav>
    <form class="flex items-center justify-center w-full" action="{{ route('auth.logout') }}" method="POST">
        @csrf
        <button type="submit"
            class="bg-red-500 text-white btn btn-sm font-bold absolute bottom-4 hover:bg-red-600 transition-all ease-linear">
            Sair
        </button>
    </form>
</aside>