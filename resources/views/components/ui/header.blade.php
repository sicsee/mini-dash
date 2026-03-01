<header class="flex items-center py-3 border-b-2 border-zinc-200 shadow-lg shadow-zinc-200 gap-x-5">
    <div class="flex flex-col text-start px-5">
        <h1 class="title text-xl sm:text-2xl">Olá, {{ auth()->user()->name }}</h1>
        <p class="subtitle">Cliente Premium</p>
    </div>
  </header>