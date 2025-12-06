@extends('layouts.admin-comic')

@section('content')

<h1 class="text-4xl font-black mb-8 text-comic-dark">USER MANAGEMENT</h1>

@if(session('status'))
    <p class="bg-comic-green text-white comic-frame p-3 font-bold mb-6">
        {{ session('status') }}
    </p>
@endif

<div class="bg-white comic-frame shadow-comic-pop-lg p-6 rounded-xl">
    <table class="w-full">
        <thead>
            <tr class="text-left text-xl font-black border-b-4 border-comic-dark">
                <th class="pb-3">Name</th>
                <th class="pb-3">Email</th>
                <th class="pb-3">Joined</th>
                <th class="pb-3">Status</th>
                <th class="pb-3">Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($users as $user)
                <tr class="border-b-2 border-comic-dark py-4">
                    <td class="py-4 font-bold">{{ $user->name }}</td>
                    <td class="py-4">{{ $user->email }}</td>
                    <td class="py-4">{{ $user->created_at->format('d M Y') }}</td>

                    <td class="py-4">
                        @if($user->banned_at)
                            <span class="bg-comic-red text-white px-3 py-1 comic-frame rounded">BANNED</span>
                        @else
                            <span class="bg-comic-green text-white px-3 py-1 comic-frame rounded">ACTIVE</span>
                        @endif
                    </td>

                    <td class="py-4">
                        <form method="POST" action="{{ route('overwatch.users.toggleBan', $user) }}">
                            @csrf

                            @if($user->banned_at)
                                <button class="comic-btn bg-comic-green text-white px-4 py-2 rounded shadow-comic-button">
                                    UNBAN
                                </button>
                            @else
                                <button class="comic-btn bg-comic-red text-white px-4 py-2 rounded shadow-comic-button">
                                    BAN
                                </button>
                            @endif

                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
</div>

@endsection
