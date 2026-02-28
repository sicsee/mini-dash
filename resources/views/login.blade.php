<x-layouts.layout>
  <main class="flex items-center justify-center h-screen w-full bg-zinc-200">
  <section class="w-1/2 h-full flex justify-center items-center">
    <div class="flex flex-col w-110 p-10 shadow-xl shadow-zinc-400 rounded-2xl bg-white">
      <h1 class="font-medium text-3xl">
        Faça Login
      </h1>

      <p class="my-2">
        Insira seus dados para acessar a sua conta
      </p>

      <form action="{{ route('auth.login')}} " method="POST" class="flex flex-col ">
        @csrf

        <div class="flex flex-col mb-4">
          <label for="email" class="font-medium italic ">Email</label>
          <input type="email" name="email" placeholder="you@email.com" class="w-full px-4 py-2 bg-white border-2 rounded-lg focus:border-blue-500 transition-all duration-200 placeholder-gray-400 text-gray-900 hover:border-gray-400 shadow-sm @error('email') border-red-500 @enderror">
          @error('email')
            <p class="text-red-500 text-sm">
              {{ $message }}
            </p>
          @enderror
        </div>

        <div class="flex flex-col mb-4">
          <label for="password" class="font-medium italic ">Senha</label>
          <input type="password" name="password" placeholder="**********" class="w-full px-4 py-2 bg-white border-2 rounded-lg focus:border-blue-500 transition-all duration-200 placeholder-gray-400 text-gray-900 hover:border-gray-400 shadow-sm @error('password') border-red-500 @enderror">
          @error('password')
            <p class="text-red-500 text-sm">
              {{ $message }}
            </p>
          @enderror
        </div>

        <button type="submit" class="bg-blue-500 h-10 rounded-lg text-white font-bold cursor-pointer hover:bg-blue-700 transition-all linear duration-300 mt-2">Entrar</button>
      </form>

      <p class="text-center mt-4">
        Não tem uma conta?
          <a href="{{ route('site.register') }}" class="underline hover:opacity-50 transition">
            Registre-se
          </a>
      </p>

    </div>
    </section>
    <section class="hidden sm:block w-1/2 h-full p-4">
     <div class="bg-center bg-cover bg-no-repeat w-full h-full rounded-2xl flex justify-center" style="background-image: url({{ asset('images/bg-black.jpg') }})">
      <div class="flex items-center justify-center gap-2">
        <x-lucide-globe class="text-white w-9 h-9"/>
        <h1 class="text-2xl font-bold text-white">Mini Dash</h1>
     </div>
    </section>
  </main>
</x-layouts.layout>


