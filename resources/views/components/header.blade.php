<header class="flex sm:justify-between space-x-4 justify-center items-center px-6 md:px-20 py-4 bg-transparent absolute top-0 left-0 z-20 w-full">
    <div class="flex items-center gap-2">
      <x-icons.globe />
      <h1 class="text-white font-bold hidden text-2xl md:block italic">Mini Dash</h1>
    </div>
    <div>
      @guest
        <a href="{{ route('auth.login') }}" class="btn btn-default btn-md">Login</a>
        <a href="{{ route('auth.login') }}" class="btn btn-secondary btn-md">Cadastrar</a>
      @endguest
      @auth
        <a href="{{ route('site.dashboard') }}" class="btn btn-secondary btn-md">Dashboard</a>
      @endauth
    </div>
  </header>