<x-layouts.layout>
    <main
        class="relative min-h-screen w-full flex flex-col items-center justify-center overflow-hidden bg-zinc-950 font-sans antialiased text-white">

        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/bg-land.png') }}" class="w-full h-full object-cover opacity-40 scale-105"
                alt="Background">
            <div class="absolute inset-0 bg-linear-to-b from-zinc-950/80 via-transparent to-zinc-950"></div>
        </div>

        <header class="fixed top-0 left-0 z-50 w-full px-6 py-5 md:px-12">
            <div
                class="mx-auto flex max-w-7xl items-center justify-between rounded-2xl border border-white/10 bg-white/5 px-6 py-3 backdrop-blur-md">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-black shadow-lg shadow-white/20">
                        <x-lucide-globe class="w-6 h-6" />
                    </div>
                    <h1 class="hidden text-xl font-black uppercase tracking-[0.2em] md:block font-manrope">Mini Dash</h1>
                </div>

                <div class="flex items-center gap-4">
                    @guest
                        <a href="{{ route('auth.login') }}"
                            class="text-[10px] font-black uppercase tracking-widest text-zinc-400 hover:text-white transition-colors">Entrar</a>
                        <a href="{{ route('auth.register') }}"
                            class="rounded-lg bg-white px-5 py-2.5 text-[10px] font-black uppercase tracking-widest text-black hover:bg-zinc-200 transition-all active:scale-95 shadow-lg shadow-white/10">
                            Começar Agora
                        </a>
                    @endguest
                    @auth
                        <a href="{{ route('site.dashboard') }}"
                            class="rounded-lg bg-white px-5 py-2.5 text-[10px] font-black uppercase tracking-widest text-black hover:bg-zinc-200 transition-all active:scale-95">
                            Ir para Dashboard
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <section class="relative z-10 flex flex-col items-center text-center px-6">
            <div
                class="mb-6 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 backdrop-blur-sm">
                <span class="relative flex h-2 w-2">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                </span>
                <span class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-400">Versão 2.0
                    Disponível</span>
            </div>

            <h2 class="max-w-4xl text-5xl md:text-8xl font-black tracking-tighter leading-[0.9] mb-8">
                Gestão mínima.<br>
                <span
                    class="bg-linear-to-r from-zinc-100 via-zinc-400 to-zinc-600 bg-clip-text text-transparent italic">Impacto
                    máximo.</span>
            </h2>

            <p class="max-w-xl text-base md:text-lg text-zinc-400 font-medium leading-relaxed mb-10">
                A plataforma definitiva para quem busca clareza operacional. Controle estoque, clientes e vendas em uma
                interface projetada para a velocidade.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 items-center justify-center">
                @guest
                    <a href="{{ route('auth.register') }}"
                        class="h-14 px-10 flex items-center justify-center rounded-2xl bg-white text-black text-xs font-black uppercase tracking-widest hover:bg-zinc-200 transition-all shadow-xl shadow-white/10 group">
                        Criar conta gratuita
                        <x-lucide-arrow-right class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" />
                    </a>
                @endguest

            </div>
        </section>

        <footer
            class="absolute bottom-10 left-0 w-full px-12 hidden md:flex justify-between items-center text-[9px] font-black uppercase tracking-[0.4em] text-zinc-600">
            <p>&copy; 2026 Mini Dash Inc.</p>
            <div class="flex gap-8">
                <a href="https://www.linkedin.com/in/sicsee/" target="_blank" class="hover:text-zinc-300">Linkedin</a>
                <a href="https://github.com/sicsee" target="_blank" class="hover:text-zinc-300">Github</a>
            </div>
        </footer>

    </main>
</x-layouts.layout>
