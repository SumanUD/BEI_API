<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BEI Admin CMS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .glass-card.dark {
            background: rgba(20, 20, 20, 0.75);
            border-color: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] flex items-center justify-center min-h-screen px-6">

    <div class="w-full max-w-md glass-card dark:glass-card p-10 rounded-2xl shadow-xl transition-all duration-300">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold mb-2">Welcome to <span class="text-[#2563eb]">BEI Admin CMS</span></h1>
            <p class="text-gray-600 dark:text-gray-300 text-sm">Please log in to continue managing the system.</p>
        </div>

        @if (Route::has('login'))
            <div class="flex flex-col gap-4">
                @auth
                    <a
                        href="{{ url('/home') }}"
                        class="w-full text-center px-6 py-3 bg-[#2563eb] hover:bg-[#1d4ed8] text-white font-medium rounded-lg transition"
                    >
                        Go to Dashboard
                    </a>
                @else
                    <a
                        href="{{ route('login') }}"
                        class="w-full text-center px-6 py-3 bg-[#2563eb] hover:bg-[#1d4ed8] text-white font-medium rounded-lg transition"
                    >
                        Log In
                    </a>


                @endauth
            </div>
        @endif
    </div>

</body>
</html>
