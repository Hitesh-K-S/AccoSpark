<!-- resources/views/landing.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>AccoSpark – AI Accountability Partner</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');

        body {
            background-color: #FEF3C7;
            font-family: 'Inter', sans-serif;
            color: #1F2937;
        }
        .comic-btn {
            transition: 0.12s ease;
            border: 4px solid #1F2937;
            font-weight: 900;
        }
        .comic-btn:active {
            transform: translate(4px,4px);
            box-shadow: none !important;
        }
        .comic-frame {
            border: 4px solid #1F2937;
        }
        .comic-icon {
            stroke-width: 4;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* Nice little comic-hover effect */
        .hover-wiggle:hover {
            transform: rotate(1deg) scale(1.02);
            transition: 0.15s;
        }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'comic-red': '#EF4444',
                        'comic-blue': '#3B82F6',
                        'comic-yellow': '#FACC15',
                        'comic-pink': '#EC4899',
                        'comic-dark': '#1F2937',
                        'comic-page': '#FEF3C7',
                    },
                    boxShadow: {
                        'comic-pop-lg': '10px 10px 0px 0px #1F2937',
                        'comic-pop-md': '6px 6px 0px 0px #1F2937',
                        'comic-button': '4px 4px 0px 0px #1F2937',
                    }
                }
            }
        }
    </script>
</head>

<body class="min-h-screen">

  <header class="bg-comic-red sticky top-0 z-20 shadow-comic-pop-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center comic-frame border-t-0 border-x-0">

        <!-- Logo -->
        <h1 class="text-3xl font-extrabold text-white tracking-widest leading-none">
            AccoSpark
        </h1>

        <!-- Desktop Nav -->
        <nav class="hidden md:flex items-center space-x-6 font-bold text-white">

            <a href="#features"
                class="px-3 py-1 rounded-md border-2 border-transparent hover:border-white transition">
                Features
            </a>

            <a href="#howitworks"
                class="px-3 py-1 rounded-md border-2 border-transparent hover:border-white transition">
                How It Works
            </a>

            <a href="#pricing"
                class="px-3 py-1 rounded-md border-2 border-transparent hover:border-white transition">
                Testimonials
            </a>

            <!-- LOGIN BUTTON (unchanged, looks fire) -->
            <a href="{{ url('/login') }}"
               class="comic-btn bg-comic-blue text-white px-4 py-2 rounded-md shadow-comic-button hover:bg-blue-500">
               LOGIN
            </a>
        </nav>

        <!-- Mobile Menu Icon -->
        <button class="md:hidden text-white">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="3" stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>
</header>


    <!-- MAIN CONTENT -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-24">

        <!-- HERO -->
        <section class="text-center pt-10 pb-16">
            <div class="bg-comic-blue text-white p-6 md:p-12 rounded-2xl mx-auto max-w-4xl shadow-comic-pop-lg comic-frame hover-wiggle">
                <p class="text-lg md:text-2xl font-bold mb-4 uppercase tracking-widest">WAKE UP, CHIEF!</p>

                <h2 class="text-5xl sm:text-7xl md:text-8xl font-black mb-8 leading-tight">
                    DEFEAT THE <span class="text-comic-yellow block md:inline-block -rotate-3 transform">PRO-CRASTINATOR!</span>
                </h2>

                <p class="text-xl md:text-3xl font-bold mb-10 max-w-2xl mx-auto">
                    Your AI Accountability Partner is here to push, remind, and cheer you across the finish line.
                </p>

                <a href="{{ url('/register') }}"
                    class="comic-btn inline-block bg-comic-yellow text-comic-dark text-2xl px-12 py-4 rounded-lg shadow-comic-button hover:bg-yellow-400 hover-wiggle">
                    GET STARTED NOW! (IT'S FREE)
                </a>
            </div>

            <p class="mt-8 text-lg font-bold text-comic-dark italic rotate-1">
                "BAM! Productivity Achieved!"
            </p>
        </section>

        <!-- FEATURES -->
        <section id="features" class="py-12 mt-10 scroll-mt-28">
            <h3 class="text-4xl font-black text-center mb-16 uppercase relative">
                <span class="relative z-10 bg-comic-page px-4 pb-1">Your New Superpowers</span>
                <span class="absolute w-full h-2 bg-comic-pink bottom-0 left-0 -translate-y-1/2 z-0"></span>
            </h3>

            <div class="grid md:grid-cols-3 gap-8 md:gap-12">

                @php
                    $features = [
                        [
                            'color' => 'bg-comic-red',
                            'title' => 'RELENTLESS REMINDERS!',
                            'desc' => "AI hits you with personalized nudges. No more 'maybe later'. It’s NOW.",
                            'icon' => '<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path>'
                        ],
                        [
                            'color' => 'bg-comic-blue',
                            'title' => 'MISSION DECONSTRUCTION',
                            'desc' => "Big goals become bite-sized missions. AI finds the easiest route to victory.",
                            'icon' => '<path d="M14.5 10c-.83 6.07-5.5 9.93-5.5 9.93..."></path>'
                        ],
                        [
                            'color' => 'bg-comic-yellow',
                            'title' => 'THE REAL SCOOP',
                            'desc' => "Honest feedback, zero shame. Just real data → real progress.",
                            'icon' => '<path d="M22 10s-2.5 1-2.5 4.5..."></path>'
                        ],
                    ];
                @endphp

                @foreach ($features as $f)
                <div class="text-center bg-white p-6 rounded-xl shadow-comic-pop-md comic-frame">
                    <div class="w-16 h-16 mx-auto mb-4 {{ $f['color'] }} rounded-full flex items-center justify-center shadow-md">
                        <svg class="w-8 h-8 text-white comic-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            {!! $f['icon'] !!}
                        </svg>
                    </div>
                    <h4 class="text-2xl font-bold mb-3">{{ $f['title'] }}</h4>
                    <p class="text-gray-700">{{ $f['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </section>

        <!-- HOW IT WORKS -->
        <section id="howitworks" class="py-12 bg-comic-dark text-white p-8 rounded-2xl comic-frame scroll-mt-28">
            <h3 class="text-4xl font-black text-center mb-16 uppercase text-comic-yellow">
                THE 3-STEP ROAD TO GLORY!
            </h3>

            <div class="grid md:grid-cols-3 gap-12">

                @php
                    $steps = [
                        ['num' => 1, 'color' => 'bg-comic-pink', 'title' => 'SET YOUR GOAL'],
                        ['num' => 2, 'color' => 'bg-comic-red', 'title' => 'FACE THE PUSH'],
                        ['num' => 3, 'color' => 'bg-comic-blue', 'title' => 'ACHIEVE VICTORY!'],
                    ];
                @endphp

                @foreach ($steps as $s)
                <div class="flex flex-col items-center text-center p-4">
                    <div class="w-20 h-20 {{ $s['color'] }} text-5xl font-black rounded-full flex items-center justify-center mb-4 shadow-xl border-4 border-comic-yellow">
                        {{ $s['num'] }}
                    </div>
                    <h4 class="text-2xl font-bold mb-2">{{ $s['title'] }}</h4>
                    <p class="text-gray-300">
                        {!! $s['num'] == 1 ? "Tell the AI what you want to conquer." :
                           ($s['num'] == 2 ? "AI pushes back on procrastination with discipline." :
                                             "Track tasks, crush goals, and evolve into your best self.") !!}
                    </p>
                </div>
                @endforeach

            </div>
        </section>

        <!-- TESTIMONIAL -->
        <section id="pricing" class="py-12 scroll-mt-28">
            <h3 class="text-4xl font-black text-center mb-16 uppercase relative">
                <span class="relative z-10 bg-comic-page px-4 pb-1">What the Heroes Say</span>
                <span class="absolute w-full h-2 bg-comic-red bottom-0 left-0 -translate-y-1/2 z-0"></span>
            </h3>

            <div class="max-w-3xl mx-auto bg-comic-blue text-white p-8 rounded-tr-[50px] rounded-bl-[50px] shadow-comic-pop-lg comic-frame hover-wiggle">
                <blockquote class="text-xl md:text-3xl italic font-semibold leading-snug">
                    "I used to scroll 3 hours and work 20 mins. Now it's the opposite. A.I. Coach is like having a drill sergeant and a best friend in one!"
                </blockquote>
                <p class="mt-6 text-2xl font-extrabold text-comic-yellow">
                    – Procrastination Pete (Now: Productive Pete!)
                </p>
            </div>
        </section>

        <!-- FINAL CTA -->
        <section class="text-center py-12">
            <h3 class="text-4xl md:text-5xl font-black mb-6 text-comic-dark">READY TO POWER UP?</h3>
            <a href="{{ url('/register') }}"
               class="comic-btn bg-comic-pink text-white text-3xl px-16 py-6 rounded-lg shadow-comic-button hover:bg-pink-600 hover-wiggle inline-block">
                JOIN THE FIGHT!
            </a>
        </section>
    </main>

    <!-- FOOTER -->
    <footer class="bg-comic-dark text-white mt-12 py-8 comic-frame border-b-0 border-x-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center md:flex justify-between items-center">
            <p class="text-lg font-bold mb-4 md:mb-0">&copy; 2025 AccoSpark – All Rights Reserved.</p>
            <div class="flex justify-center space-x-6">
                <a href="#" class="text-comic-yellow hover:text-white font-bold transition">Privacy</a>
                <a href="#" class="text-comic-yellow hover:text-white font-bold transition">Terms</a>
                <a href="#" class="text-comic-yellow hover:text-white font-bold transition">Contact Us</a>
            </div>
        </div>
    </footer>

</body>
</html>
