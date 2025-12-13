@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">



    <!-- Check-in Card -->
    <div class="bg-white p-8 rounded-2xl comic-frame shadow-comic-pop-md">

        <h2 class="text-3xl font-black text-comic-dark mb-8 text-center">
            How did today go?
        </h2>

        <!-- Status Buttons -->
        <div id="status-buttons" class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <button onclick="selectStatus('completed')"
                class="comic-btn bg-comic-green text-white py-6 rounded-xl shadow-comic-button text-2xl font-black">
                ✅ COMPLETED
            </button>

            <button onclick="selectStatus('partial')"
                class="comic-btn bg-comic-yellow text-comic-dark py-6 rounded-xl shadow-comic-button text-2xl font-black">
                ⚠️ PARTIALLY
            </button>

            <button onclick="selectStatus('missed')"
                class="comic-btn bg-comic-red text-white py-6 rounded-xl shadow-comic-button text-2xl font-black">
                ❌ MISSED
            </button>

        </div>

        
        @if (session('ai_response'))
            <div class="mb-8 bg-comic-blue text-white p-6 rounded-xl comic-frame shadow-comic-pop-md text-center">
                <p class="text-xl font-bold">
                    🤖 {{ session('ai_response') }}
                </p>
            </div>
        @endif



        <!-- Follow-up Section -->
        <form method="POST" action="{{ route('checkin.store') }}">
            @csrf

            <input type="hidden" name="status" id="status-input">

            <div id="followup" class="hidden mt-10">

                <h3 id="followup-question"
                    class="text-2xl font-black mb-4 text-comic-dark">
                </h3>

                <textarea
                    name="user_input"
                    class="w-full comic-frame p-4 text-black font-bold rounded-lg focus:outline-none"
                    rows="4"
                    placeholder="Type honestly. No one’s judging."></textarea>

                <button
                    class="comic-btn bg-comic-blue text-white px-8 py-4 mt-6 rounded-lg shadow-comic-button text-xl font-black">
                    SUBMIT CHECK-IN
                </button>

            </div>
        </form>


        <p class="mt-8 text-sm font-bold italic text-gray-600 text-center">
            No penalties. No streaks lost. Just reality.
        </p>

    </div>

</div>

<script>
    function selectStatus(status) {
        document.getElementById('status-buttons')
            .classList.add('opacity-40', 'pointer-events-none');

        document.getElementById('status-input').value = status;

        const followup = document.getElementById('followup');
        const question = document.getElementById('followup-question');

        followup.classList.remove('hidden');

        if (status === 'completed') {
            question.innerText = "Nice. What made today work?";
        } else if (status === 'partial') {
            question.innerText = "What part did you manage to finish?";
        } else {
            question.innerText = "What blocked you today?";
        }
    }
</script>

@endsection
