<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Furnivo') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#F5F5DC] text-[#333333]">
        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10">
            <a href="{{ route('home') }}" class="mb-8 inline-flex items-center gap-3 text-wood font-semibold text-2xl">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-3xl bg-wood text-white">F</span>
                Furnivo
            </a>
            <div class="w-full max-w-md rounded-[28px] border border-[#E9E5DD] bg-white p-8 shadow-soft">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
