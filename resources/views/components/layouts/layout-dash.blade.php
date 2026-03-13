<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="relative min-h-screen pl-14">

    <x-ui.aside />

    <div x-data="{ activeModal: null }" x-cloak>
        {{ $slot }}
    </div>

    <x-ui.toast />

    @livewireScripts

</body>
</html>