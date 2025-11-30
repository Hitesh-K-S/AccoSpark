<!DOCTYPE html>
<html>
<head>
    <title>Register - Join the AI Squad</title>
    @include('auth.comic-style')
</head>

<body class="min-h-screen flex items-center justify-center p-4">
<!-- COMIC BAM! HOME ICON BUTTON -->
<a href="{{ url('/') }}"
   class="absolute top-4 left-4 bg-comic-yellow text-comic-dark w-14 h-14 
          flex items-center justify-center rounded-xl 
          border-4 border-comic-dark shadow-[6px_6px_0_0_#1F2937]
          hover:scale-105 hover:shadow-[3px_3px_0_0_#1F2937]
          active:translate-x-1 active:translate-y-1 active:shadow-none
          transition-all duration-150 ease-out
          rotate-[-3deg] hover:rotate-0 cursor-pointer select-none">
    
    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="4" stroke-linecap="round" stroke-linejoin="round"
              d="M3 9.5l9-7 9 7M4 10v10h6V14h4v6h6V10"/>
    </svg>
</a>



    <div class="max-w-md w-full">
        <div class="bg-comic-pink text-white p-8 rounded-2xl shadow-comic-pop-lg comic-frame text-center">

            <h1 class="text-4xl font-extrabold mb-2">
                <span class="bg-comic-yellow px-3 pb-1 pt-0.5 rounded-md shadow-md -rotate-2 inline-block">JOIN</span>
                THE SQUAD
            </h1>

            <p class="text-xl font-bold mb-4">Your journey starts now.</p>

            @if ($errors->any())
            <div class="mb-6 bg-comic-red text-white p-3 rounded-md comic-frame">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4 mb-3">
                @csrf

                <input name="name" type="text" placeholder="Name" required
                    class="w-full p-3 rounded-lg comic-input">

                <input name="email" type="email" placeholder="Email" required
                    class="w-full p-3 rounded-lg comic-input">

                <input name="password" type="password" placeholder="Password" required
                    class="w-full p-3 rounded-lg comic-input">

                <input name="password_confirmation" type="password" placeholder="Confirm Password" required
                    class="w-full p-3 rounded-lg comic-input">

                <button class="comic-btn bg-comic-yellow text-comic-dark text-xl w-full py-3 rounded-lg shadow-comic-button">
                    CREATE ACCOUNT
                </button>
            </form>

            <a href="{{ route('google.login') }}"
               class="mb-3 comic-btn bg-white text-comic-dark w-full text-center text-xl py-3 rounded-lg shadow-comic-button hover:bg-gray-100 flex items-center justify-center space-x-3">

                <svg class="w-6 h-6" viewBox="0 0 533.5 544.3">
                    <path fill="#4285F4" d="M533.5 278.4c0-17.4-1.5-34.1-4.3-50.4H272v95.4h146.9c-6.3 34-25 62.8-53.1 82.1l86 66.8c50.2-46.3 81.7-114.7 81.7-193.9z"/>
                    <path fill="#34A853" d="M272 544.3c72.5 0 133.4-23.9 177.9-64.9l-86-66.8c-23.8 16-54.3 25.5-91.9 25.5-70.7 0-130.7-47.7-152-111.3l-89 68.3c43.5 86.1 133 149.2 241 149.2z"/>
                    <path fill="#FBBC05" d="M120 326.8c-10-29.9-10-62.1 0-92l-89-68.3C-8 225.6-8 318.7 31 410.4l89-68.3z"/>
                    <path fill="#EA4335" d="M272 109.6c38.8 0 73.7 13.4 101.1 39.8l75.7-75.7C405.3 26 344.5 0 272 0c-108 0-197.5 63.1-241 149.2l89 68.3c21.3-63.6 81.3-111.3 152-111.3z"/>
                </svg>

                <span class="font-black">Sign in with Google</span>
            </a>

            <p class="mt-6 text-sm font-medium">
                Already have an account?
                <a class="text-comic-dark font-bold underline" href="{{ route('login') }}">Login here</a>
            </p>

        </div>
    </div>

</body>
</html>
