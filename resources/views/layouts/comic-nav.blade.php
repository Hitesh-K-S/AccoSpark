<!-- Comic Authenticated Navbar -->
<nav class="bg-comic-red shadow-comic-pop-md comic-frame border-t-0 border-x-0 sticky top-0 z-20">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

        <!-- Logo -->
        <a href="/dashboard" class="text-3xl font-extrabold text-white tracking-widest hover:opacity-80">
            AccoSpark
        </a>

        <!-- Desktop Links -->
        <div class="hidden md:flex items-center space-x-6 font-bold text-white">

            <a href="/dashboard"
               class="px-3 py-1 rounded-md border-2 border-transparent hover:border-white transition">
                Dashboard
            </a>

            <a href="/goals"
               class="px-3 py-1 rounded-md border-2 border-transparent hover:border-white transition">
                Goals
            </a>

            <a href="/checkin"
               class="px-3 py-1 rounded-md border-2 border-transparent hover:border-white transition">
                Daily Check-In
            </a>

            <a href="/profile"
               class="px-3 py-1 rounded-md border-2 border-transparent hover:border-white transition">
                Profile
            </a>

            <!-- LOGOUT -->
            <form action="/logout" method="POST">
                @csrf
                <button 
                    class="comic-btn bg-white text-comic-dark px-4 py-2 rounded-md shadow-comic-button hover:bg-gray-100">
                    Logout
                </button>
            </form>
        </div>

        <!-- Mobile Menu -->
        <button class="md:hidden text-white">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="3" stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>
</nav>
