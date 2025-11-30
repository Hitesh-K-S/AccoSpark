<!DOCTYPE html>
<html>
<head>
    <title>Verify Email</title>
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

        <div class="bg-comic-green text-white p-8 rounded-2xl shadow-comic-pop-lg comic-frame text-center">

            <h1 class="text-4xl font-extrabold mb-4">
                <span class="bg-comic-dark px-3 pb-1 pt-0.5 rounded-md shadow-md -rotate-2 inline-block">
                    VERIFY
                </span>
                EMAIL
            </h1>

            <p class="text-lg font-bold mb-6">
                We sent you a verification link. Check your inbox!
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 bg-comic-yellow text-comic-dark p-3 rounded-md comic-frame">
                    A new verification link was sent.
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button class="comic-btn bg-white text-comic-dark text-xl w-full py-3 rounded-lg shadow-comic-button">
                    RESEND LINK
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button class="comic-btn bg-comic-red text-white w-full py-3 rounded-lg">
                    LOGOUT
                </button>
            </form>
        </div>

    </div>

</body>
</html>
