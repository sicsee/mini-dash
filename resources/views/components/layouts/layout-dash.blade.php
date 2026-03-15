<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body x-data="{ mobileMenu: false, activeModal: null }" class="relative min-h-screen bg-white text-black antialiased">

    <div x-show="mobileMenu" x-cloak @click="mobileMenu = false"
        class="fixed inset-0 bg-black/20 backdrop-blur-sm z-40 lg:hidden"></div>

    <div class="flex">

        <x-ui.aside />

        <div class="flex-1 flex flex-col min-w-0">

            <header
                class="flex items-center justify-between px-6 py-4 border-b border-zinc-100 lg:hidden bg-white/80 backdrop-blur-md sticky top-0 z-30">
                <div class="flex items-center gap-2">
                    <div class="w-1.5 h-1.5 bg-black rounded-full"></div>
                    <span class="text-[10px] font-black uppercase tracking-[0.3em]">Sistema</span>
                </div>
                <button @click="mobileMenu = true" class="p-2">
                    <x-lucide-menu class="w-6 h-6 text-black" />
                </button>
            </header>

            <main class="w-full p-4 lg:p-0">
                {{ $slot }}
            </main>
        </div>
    </div>

    <x-ui.toast />
    @livewireScripts
</body>

</html>
