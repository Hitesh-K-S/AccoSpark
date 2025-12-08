@extends('layouts.admin-comic')

@section('content')
<div class="max-w-5xl mx-auto py-10">
    
    <!-- Header -->
    <div class="flex items-center justify-between mb-10">
        <h1 class="text-4xl font-black text-comic-dark">
            AI Personas
        </h1>

        <a href="{{ route('overwatch.personas.create') }}"
            class="comic-btn bg-comic-yellow px-6 py-3 rounded-lg text-xl shadow-comic-button hover:bg-yellow-400">
            + Add Persona
        </a>
    </div>

    <!-- Persona List -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach($personas as $persona)
            <div class="bg-white p-6 rounded-xl comic-frame shadow-comic-pop-md hover:scale-[1.02] transition duration-200">

                <div class="flex justify-between items-start mb-4">
                    <h2 class="text-2xl font-black">
                        {{ $persona->name }}
                    </h2>

                    <!-- Active/Inactive badge -->
                    @if($persona->is_active)
                        <span class="bg-comic-green text-white px-3 py-1 rounded-lg text-sm font-black comic-frame">
                            ACTIVE
                        </span>
                    @else
                        <span class="bg-comic-red text-white px-3 py-1 rounded-lg text-sm font-black comic-frame">
                            DISABLED
                        </span>
                    @endif
                </div>

                <p class="text-gray-700 font-medium line-clamp-3 mb-6">
                    {{ $persona->system_prompt }}
                </p>

                <div class="flex items-center justify-between">
                    
                    <!-- Edit -->
                    <a href="{{ route('overwatch.personas.edit', $persona->id) }}"
                       class="comic-btn bg-comic-blue text-white px-4 py-2 rounded-md shadow-comic-button hover:bg-blue-600">
                        Edit
                    </a>

                    <!-- Toggle -->
                    <form action="{{ route('overwatch.personas.toggle', $persona->id) }}" method="POST">
                        @csrf
                        <button class="comic-btn px-4 py-2 rounded-md shadow-comic-button
                            {{ $persona->is_active ? 'bg-comic-red text-white' : 'bg-comic-green text-white' }}">
                            {{ $persona->is_active ? 'Disable' : 'Enable' }}
                        </button>
                    </form>

                    <!-- Delete -->
                    <button onclick="confirmDelete({{ $persona->id }})"
                        class="comic-btn bg-comic-pink text-white px-4 py-2 rounded-md shadow-comic-button hover:bg-pink-600">
                        Delete
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white comic-frame p-8 rounded-xl max-w-md shadow-comic-pop-lg text-center scale-95 animate-[pop_0.15s_ease-out_forwards]">

        <h2 class="text-3xl font-black text-comic-red mb-4">
            BAM! DELETE?
        </h2>

        <p class="text-gray-700 font-semibold mb-6">
            This persona will be gone forever. No take-backs.
        </p>

        <form method="POST" id="deleteForm">
            @csrf
            @method('DELETE')

            <button type="submit"
                class="comic-btn bg-comic-red text-white px-6 py-3 rounded-lg shadow-comic-button hover:bg-red-600 text-xl">
                Yes, DESTROY IT
            </button>

            <button type="button"
                onclick="closeDeleteModal()"
                class="comic-btn bg-gray-300 text-comic-dark px-6 py-3 rounded-lg shadow-comic-button ml-4">
                Cancel
            </button>
        </form>

    </div>
</div>

<script>
    function confirmDelete(id) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        form.action = `/overwatch/personas/${id}`;
        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>

@endsection
