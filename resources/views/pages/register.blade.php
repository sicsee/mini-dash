<x-layouts.layout>
    <main class="min-h-screen w-full bg-zinc-50 flex items-center justify-center p-4 md:p-0">

        <section class="hidden md:block md:w-1/2 h-screen p-6">
            <div class="relative w-full h-full rounded-[40px] overflow-hidden bg-zinc-900 shadow-2xl">
                <div class="absolute inset-0 bg-linear-to-br from-black/60 to-transparent z-10"></div>
                <div class="absolute inset-0 bg-center bg-cover bg-no-repeat transition-transform duration-700 hover:scale-105"
                    style="background-image: url({{ asset('images/bg-black.jpg') }})">
                </div>

                <div class="absolute bottom-12 left-12 z-20 text-white">
                    <div class="flex items-center gap-3 mb-2">
                        <x-lucide-globe class="w-6 h-6 text-white/50" />
                        <span class="text-[10px] font-black uppercase tracking-[0.4em]">Nova Jornada</span>
                    </div>
                    <h2 class="text-4xl font-black tracking-tighter">Crie sua conta.</h2>
                    <p class="mt-2 text-white/40 text-[10px] font-black uppercase tracking-widest leading-loose">
                        Junte-se a centenas de usuários<br>na gestão inteligente de ativos.
                    </p>
                </div>
            </div>
        </section>

        <section class="w-full md:w-1/2 h-full flex justify-center items-center py-10">
            <div
                class="w-full max-w-md bg-white p-8 md:p-12 rounded-[32px] md:rounded-none shadow-2xl shadow-zinc-200 md:shadow-none transition-all">

                <header class="mb-8 text-center md:text-left">
                    <h1 class="text-4xl font-black tracking-tighter text-zinc-900">Registre-se</h1>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400 mt-3">Comece a gerenciar hoje
                        mesmo</p>
                </header>

                <form action="{{ route('auth.register') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-widest text-zinc-400 ml-1">Nome
                            Completo</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            placeholder="Ex: João Silva"
                            class="w-full h-12 px-5 rounded-2xl border  bg-zinc-50 focus:bg-white focus:border-black transition-all text-sm font-bold outline-none @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label
                            class="text-[10px] font-black uppercase tracking-widest text-zinc-400 ml-1">E-mail</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            placeholder="you@email.com"
                            class="w-full h-12 px-5 rounded-2xl border  bg-zinc-50 focus:bg-white focus:border-black transition-all text-sm font-bold outline-none @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label
                                class="text-[10px] font-black uppercase tracking-widest text-zinc-400 ml-1">Senha</label>
                            <input type="password" name="password" required placeholder="••••••••"
                                class="w-full h-12 px-5 rounded-2xl border  bg-zinc-50 focus:bg-white focus:border-black transition-all text-sm font-bold outline-none @error('password') border-red-500 @enderror">
                        </div>
                        <div class="space-y-1.5">
                            <label
                                class="text-[10px] font-black uppercase tracking-widest text-zinc-400 ml-1">Confirmar</label>
                            <input type="password" name="password_confirmation" required placeholder="••••••••"
                                class="w-full h-12 px-5 rounded-2xl border border-zinc-100 bg-zinc-50 focus:bg-white focus:border-black transition-all text-sm font-bold outline-none">
                        </div>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror

                    <button type="submit"
                        class="w-full h-14 bg-black text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-zinc-800 transition-all shadow-xl shadow-zinc-200 mt-4">
                        Criar minha conta
                    </button>
                </form>

                <footer class="mt-8 text-center md:text-left">
                    <p class="text-xs font-bold text-zinc-400">
                        Já possui uma conta?
                        <a href="{{ route('site.login') }}"
                            class="text-black underline underline-offset-4 hover:opacity-60 transition font-black">
                            Fazer Login
                        </a>
                    </p>
                </footer>
            </div>
        </section>
    </main>
</x-layouts.layout>
