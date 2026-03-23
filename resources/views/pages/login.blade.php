<x-layouts.layout>
    <main class="min-h-screen w-full bg-zinc-50 flex items-center justify-center p-4 md:p-0">

        <section class="w-full md:w-1/2 h-full flex justify-center items-center">
            <div
                class="w-full max-w-md bg-white p-8 md:p-12 rounded-[32px] md:rounded-none shadow-2xl shadow-zinc-200 md:shadow-none transition-all">

                <header class="mb-10 text-center md:text-left">
                    <h1 class="text-4xl font-black tracking-tighter text-zinc-900">
                        Acesse a <span class="font-serif italic font-light">Plataforma</span>
                    </h1>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400 mt-3">
                        Insira suas credenciais de acesso
                    </p>
                </header>

                <form action="{{ route('auth.login') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="space-y-1.5">
                        <label for="email"
                            class="text-[10px] font-black uppercase tracking-widest text-zinc-400 ml-1">E-mail
                            Corporativo</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            placeholder="exemplo@dominio.com"
                            class="w-full h-14 px-6 rounded-2xl border  bg-zinc-50 focus:bg-white focus:border-black transition-all text-sm font-bold outline-none @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="password"
                            class="text-[10px] font-black uppercase tracking-widest text-zinc-400 ml-1">Senha de
                            Segurança</label>
                        <input type="password" name="password" required placeholder="••••••••••••"
                            class="w-full h-14 px-6 rounded-2xl border  bg-zinc-50 focus:bg-white focus:border-black transition-all text-sm font-bold outline-none @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full h-14 bg-black text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-zinc-800 transition-all shadow-xl shadow-zinc-200 mt-4">
                        Entrar no Sistema
                    </button>
                </form>

                <footer class="mt-10 text-center">
                    <p class="text-xs font-bold text-zinc-400">
                        Não possui acesso?
                        <a href="{{ route('site.register') }}"
                            class="text-black underline underline-offset-4 hover:opacity-60 transition font-black">
                                Crie sua conta
                        </a>
                    </p>
                </footer>
            </div>
        </section>

        <section class="hidden md:block md:w-1/2 h-screen p-6">
            <div class="relative w-full h-full rounded-[40px] overflow-hidden bg-zinc-900 shadow-2xl">
                <div class="absolute inset-0 bg-linear-to-t from-black/80 via-black/20 to-transparent z-10"></div>

                <div class="absolute inset-0 bg-center bg-cover bg-no-repeat transition-transform duration-700 hover:scale-105"
                    style="background-image: url({{ asset('images/bg-black.jpg') }})">
                </div>

                <div class="absolute inset-0 z-20 flex flex-col items-center justify-center text-white p-12">
                    <div class="flex items-center gap-3 mb-4">
                        <x-lucide-globe class="w-8 h-8 text-white/50 animate-pulse" />
                        <span class="text-xs font-black uppercase tracking-[0.5em]">Global Control</span>
                    </div>
                    <h2 class="text-5xl font-black tracking-tighter">Mini Dash</h2>
                    <p
                        class="mt-4 text-white/40 text-[10px] font-black uppercase tracking-widest text-center leading-loose">
                        Gestão simplificada com<br>estética contemporânea
                    </p>
                </div>
            </div>
        </section>
    </main>
</x-layouts.layout>
