
<x-layouts.layout>
  <main class="flex items-center justify-center h-screen w-full bg-zinc-200">
    <section class="hidden sm:block w-1/2 h-full p-4">
      <div class="bg-center bg-cover bg-no-repeat w-full h-full rounded-2xl flex justify-center" style="background-image: url({{ asset('images/bg-black.jpg') }})">
       <div class="flex items-center justify-center gap-2">
        <x-lucide-globe class="text-white w-9 h-9"/>
         <h1 class="text-2xl font-bold text-white">Mini Dash</h1>
      </div>
     </section>
     
    <section class="w-1/2 h-full flex justify-center items-center">
      <div class="flex flex-col w-110 p-10 shadow-xl shadow-zinc-400 rounded-2xl bg-white">
        <h1 class="font-medium text-3xl">
          Registre-se
        </h1>
  
        <p class="my-2">
          Preencha suas informações para se cadastrar seus hábitos
        </p>
  
        <form action="{{ route('auth.register') }}" method="POST" class="flex flex-col ">
          @csrf
  
          <div class="flex flex-col gap-2 mb-4">
            <label for="name">Nome</label>
            <input type="text" name="name" placeholder="Seu nome" class="w-full px-4 py-2 bg-white border-2 rounded-lg focus:border-blue-500 transition-all duration-200 placeholder-gray-400 text-gray-900 hover:border-gray-400 shadow-sm @error('name') border-red-500 @enderror">
            @error('name')
              <p class="text-red-500 text-sm">
                {{ $message }}
              </p>
            @enderror
          </div>
  
          <div class="flex flex-col gap-2 mb-4">
            <label for="email">Email</label>
            <input type="email" name="email" placeholder="you@email.com" class="w-full px-4 py-2 bg-white border-2 rounded-lg focus:border-blue-500 transition-all duration-200 placeholder-gray-400 text-gray-900 hover:border-gray-400 shadow-sm @error('email') border-red-500 @enderror">
            @error('email')
              <p class="text-red-500 text-sm">
                {{ $message }}
              </p>
            @enderror
          </div>
  
          <div class="flex flex-col gap-2 mb-4">
            <label for="password">Senha</label>
            <input type="password" name="password" placeholder="**********" class="w-full px-4 py-2 bg-white border-2 rounded-lg focus:border-blue-500 transition-all duration-200 placeholder-gray-400 text-gray-900 hover:border-gray-400 shadow-sm @error('password') border-red-500 @enderror">
            @error('password')
              <p class="text-red-500 text-sm">
                {{ $message }}
              </p>
            @enderror
          </div>
  
          <div class="flex flex-col gap-2 mb-4">
            <label for="password_confirmation">Repita sua senha</label>
            <input type="password" name="password_confirmation" placeholder="**********" class="w-full px-4 py-2 bg-white border-2 rounded-lg focus:border-blue-500 transition-all duration-200 placeholder-gray-400 text-gray-900 hover:border-gray-400 shadow-sm @error('password') border-red-500 @enderror">
            @error('password')
              <p class="text-red-500 text-sm">
                {{ $message }}
              </p>
            @enderror
          </div>
  
          <button type="submit" class="bg-blue-500 h-10 rounded-lg text-white font-bold cursor-pointer hover:bg-blue-700 transition-all linear duration-300 mt-2">Cadastrar</button>
        </form>
  
        <p class="text-center mt-4">
          Já tem uma conta?
            <a href="{{ route('site.login') }}" class="underline hover:opacity-50 transition">
              Faça Login
            </a>
        </p>
      </div>
    </section>
  </main>
</x-layouts.layout>

  