@extends('layouts.admin-auth')

@section('content')
<div class="max-w-md mx-auto mt-20 bg-comic-blue text-white p-8 rounded-2xl shadow-comic-pop-lg comic-frame">

    <h1 class="text-4xl font-black text-center mb-6">
        OVERWATCH LOGIN
    </h1>

    @if($errors->any())
        <p class="bg-comic-red comic-frame p-3 text-white font-bold mb-4">
            {{ $errors->first() }}
        </p>
    @endif

    <form method="POST" action="{{ route('overwatch.login.post') }}">
        @csrf

        <input type="email" name="email" placeholder="Admin Email"
               class="comic-input w-full p-3 rounded mb-4 text-black" required>

        <input type="password" name="password" placeholder="Password"
               class="comic-input w-full p-3 rounded mb-6 text-black" required>

        <button class="comic-btn bg-comic-yellow text-comic-dark w-full py-3 text-xl rounded shadow-comic-button">
            LOGIN
        </button>
    </form>
</div>
@endsection
