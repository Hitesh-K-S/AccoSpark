<!DOCTYPE html>
<html lang="en">
<head>
    @include('auth.comic-style')

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Overwatch Admin - AccoSpark</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>

<body class="min-h-screen bg-comic-page">

    <!-- Admin Navbar -->
    <header class="bg-comic-dark text-white comic-frame border-t-0 border-x-0 py-4 shadow-comic-pop-md">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">

            <h1 class="text-3xl font-black tracking-wide">
                OVERWATCH <span class="text-comic-yellow">ADMIN</span>
            </h1>

            <nav class="flex space-x-6 font-extrabold">
                <a href="{{ route('overwatch.dashboard') }}" 
                   class="hover:text-comic-yellow transition">Dashboard</a>

                <a href="{{ route('overwatch.users.index') }}" 
                   class="hover:text-comic-yellow transition">Users</a>

                <a href="{{ route('overwatch.personas.index') }}" 
                   class="hover:text-comic-yellow transition">Personas</a>

                <form method="POST" action="{{ route('overwatch.logout') }}">
                    @csrf
                    <button class="comic-btn bg-comic-red text-white px-4 py-1 rounded shadow-comic-button">
                        Logout
                    </button>
                </form>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-10">
        @yield('content')
    </main>

</body>
</html>
