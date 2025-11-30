<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
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

        <div class="bg-white text-comic-dark p-8 rounded-2xl shadow-comic-pop-lg comic-frame text-center">

            <h1 class="text-4xl font-extrabold mb-6">
                <span class="bg-comic-blue text-white px-3 pb-1 pt-0.5 rounded-md shadow-md -rotate-2 inline-block">
                    NEW
                </span>
                PASSWORD
            </h1>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <input name="email" type="email" placeholder="Email" required
                       value="{{ old('email', $request->email) }}"
                       class="w-full p-3 rounded-lg comic-input">

                <input name="password" type="password" placeholder="New Password" required
                       class="w-full p-3 rounded-lg comic-input">

                <input name="password_confirmation" type="password" placeholder="Confirm Password" required
                       class="w-full p-3 rounded-lg comic-input">

                <button class="comic-btn bg-comic-red text-white text-xl w-full py-3 rounded-lg shadow-comic-button">
                    RESET PASSWORD
                </button>
            </form>

        </div>
    </div>

</body>
</html>
