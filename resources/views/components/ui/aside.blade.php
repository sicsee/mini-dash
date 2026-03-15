<aside :class="mobileMenu ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed lg:sticky top-0 left-0 z-50 flex flex-col w-20 h-screen bg-white border-r border-zinc-100 shadow-2xl lg:shadow-none transition-transform duration-300 ease-in-out p-4 pt-8">

    <a class="flex items-center justify-center w-12 h-12 mx-auto bg-black rounded-2xl hover:scale-105 transition-all shadow-lg shadow-zinc-300"
        href="{{ route('site.index') }}">
        <x-lucide-globe class="text-white w-6 h-6" />
    </a>

    <nav class="mt-12 flex flex-col items-center gap-6">
        @php
            $menus = [
                ['route' => 'site.dashboard', 'icon' => 'home', 'label' => 'Dash'],
                ['route' => 'stocks.index', 'icon' => 'archive', 'label' => 'Estoque'],
                ['route' => 'products.index', 'icon' => 'package', 'label' => 'Produtos'],
                ['route' => 'customers.index', 'icon' => 'user', 'label' => 'Clientes'],
                ['route' => 'sales.index', 'icon' => 'dollar-sign', 'label' => 'Vendas'],
            ];
        @endphp

        @foreach ($menus as $menu)
            <a href="{{ route($menu['route']) }}"
                class="group relative flex items-center justify-center w-12 h-12 rounded-2xl transition-all {{ Route::is($menu['route']) ? 'bg-zinc-100 text-black' : 'text-zinc-400 hover:bg-zinc-50 hover:text-black' }}">

                @switch($menu['icon'])
                    @case('home')
                        <x-lucide-home class="w-6 h-6" />
                    @break

                    @case('archive')
                        <x-lucide-archive class="w-6 h-6" />
                    @break

                    @case('package')
                        <x-lucide-package class="w-6 h-6" />
                    @break

                    @case('user')
                        <x-lucide-user class="w-6 h-6" />
                    @break

                    @case('dollar-sign')
                        <x-lucide-dollar-sign class="w-6 h-6" />
                    @break
                @endswitch

                <span
                    class="absolute left-16 px-2 py-1 bg-black text-white text-[10px] font-black uppercase tracking-widest rounded opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity hidden lg:block">
                    {{ $menu['label'] }}
                </span>
            </a>
        @endforeach
    </nav>

    <div class="mt-auto pb-4 flex justify-center">
        <form action="{{ route('auth.logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-10 h-10 flex items-center justify-center bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all group">
                <x-lucide-log-out class="w-5 h-5" />
            </button>
        </form>
    </div>
</aside>
