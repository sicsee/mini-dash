<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="relative min-h-screen bg-white antialiased text-black font-sans">


    <div x-data="{ activeModal: null }" x-cloak class="flex flex-col min-h-screen px-4 md:px-0">


        <div class="flex-1 w-full mx-auto">
            {{ $slot }}
        </div>

    </div>

    <x-ui.toast />

    @livewireScripts
</body>

</html>
