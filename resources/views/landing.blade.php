<!-- resources/views/landing.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>AI Accountability Partner - Stop Procrastinating Now!</title>
    <!-- Tailwind via CDN (fast for demo) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');
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
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body { background-color: #FEF3C7; font-family: 'Inter', sans-serif; color: #1F2937; }
        .comic-btn { transition: all 0.1s ease; border-width: 4px; border-style: solid; border-color: #1F2937; font-weight: 900; }
        .comic-btn:active { transform: translate(4px,4px); box-shadow: none !important; }
        .comic-frame { border-width: 4px; border-style: solid; border-color: #1F2937; }
        .comic-icon { stroke-width: 4; stroke-linecap: round; stroke-linejoin: round; }
    </style>
</head>
<body class="min-h-screen">

    <!-- Header / Navigation -->
    <header class="bg-comic-red sticky top-0 z-10 shadow-comic-pop-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center comic-frame border-t-0 border-x-0">
            <h1 class="text-3xl font-extrabold text-white tracking-widest leading-none">
                <span class="bg-comic-dark px-2 pb-1 pt-0.5 rounded-md shadow-lg mr-1 transform -rotate-2 inline-block">A.I.</span>
                COACH
            </h1>
            <nav class="hidden md:flex space-x-6 font-bold text-white">
                <a href="#features" class="hover:text-comic-dark transition duration-150">Features</a>
                <a href="#howitworks" class="hover:text-comic-dark transition duration-150">How It Works</a>
                <a href="#pricing" class="hover:text-comic-dark transition duration-150">Pricing</a>
            </nav>
            <a href="#" class="md:hidden text-white text-xl font-bold">Menu</a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-24">
        <!-- Hero -->
        <section class="text-center pt-10 pb-16">
            <div class="bg-comic-blue text-white p-6 md:p-12 rounded-2xl mx-auto max-w-4xl shadow-comic-pop-lg comic-frame">
                <p class="text-lg md:text-2xl font-bold mb-4 uppercase tracking-widest">WAKE UP, CHIEF!</p>
                <h2 class="text-5xl sm:text-7xl md:text-8xl font-black mb-8 leading-tight">
                    DEFEAT THE <span class="text-comic-yellow block md:inline-block -rotate-3 transform">PRO-CRASTINATOR!</span>
                </h2>
                <p class="text-xl md:text-3xl font-bold mb-10 max-w-2xl mx-auto">
                    Your AI Accountability Partner is here to push, remind, and cheer you across the finish line. No excuses!
                </p>

                <!-- CTA Button -->
                <a href="{{ url('/register') }}" class="comic-btn inline-block bg-comic-yellow text-comic-dark text-2xl px-12 py-4 rounded-lg shadow-comic-button hover:bg-yellow-400">
                    GET STARTED NOW! (IT'S FREE)
                </a>
            </div>
            <p class="mt-8 text-lg font-bold text-comic-dark italic transform rotate-1 tracking-wider">"BAM! Productivity Achieved!"</p>
        </section>

        <!-- Features -->
        <section id="features" class="py-12">
            <h3 class="text-4xl font-black text-center mb-16 uppercase relative">
                <span class="relative z-10 bg-comic-page px-4 pb-1">Your New Superpowers</span>
                <span class="absolute w-full h-2 bg-comic-pink bottom-0 left-0 transform -translate-y-1/2 z-0"></span>
            </h3>

            <div class="grid md:grid-cols-3 gap-8 md:gap-12">
                <div class="text-center bg-white p-6 rounded-xl shadow-comic-pop-md comic-frame transform hover:scale-[1.03] transition duration-300">
                    <div class="w-16 h-16 mx-auto mb-4 bg-comic-red rounded-full flex items-center justify-center shadow-md">
                        <svg class="w-8 h-8 text-white comic-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                    </div>
                    <h4 class="text-2xl font-bold mb-3">RELENTLESS REMINDERS!</h4>
                    <p class="text-gray-700">Our AI hits you with personalized, timely nudges across all your devices. Forget 'maybe later'—it's 'NOW!'</p>
                </div>

                <div class="text-center bg-white p-6 rounded-xl shadow-comic-pop-md comic-frame transform hover:scale-[1.03] transition duration-300">
                    <div class="w-16 h-16 mx-auto mb-4 bg-comic-blue rounded-full flex items-center justify-center shadow-md">
                        <svg class="w-8 h-8 text-white comic-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14.5 10c-.83 6.07-5.5 9.93-5.5 9.93 1.05-.48 2.08-.85 3-1.15l.44-1.16"></path><path d="M11.37 13.91C10.74 15.66 9.4 17.5 7.5 17.5c-1.84 0-3-.5-4.5-2.5 2-.5 4-.5 6-.5 1.5 0 2.91.43 4.14 1.14"></path><path d="M16 5l-4-4-4 4"></path><path d="M12 1v14"></path></svg>
                    </div>
                    <h4 class="text-2xl font-bold mb-3">MISSION DECONSTRUCTION</h4>
                    <p class="text-gray-700">Big tasks get diced into micro-missions. The AI maps out the easiest path to victory, one step at a time.</p>
                </div>

                <div class="text-center bg-white p-6 rounded-xl shadow-comic-pop-md comic-frame transform hover:scale-[1.03] transition duration-300">
                    <div class="w-16 h-16 mx-auto mb-4 bg-comic-yellow rounded-full flex items-center justify-center shadow-md">
                        <svg class="w-8 h-8 text-comic-dark comic-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M22 10s-2.5 1-2.5 4.5S17.5 19 15 19s-4.5-2-4.5-6.5S9.5 5 7 5s-2.5 1-2.5 4.5S2 15 2 15"></path><path d="M2 15h20"></path></svg>
                    </div>
                    <h4 class="text-2xl font-bold mb-3">THE REAL SCOOP</h4>
                    <p class="text-gray-700">Get honest, data-driven feedback on your habits without the judgment. Just results. Just progress.</p>
                </div>
            </div>
        </section>

        <!-- How it works -->
        <section id="howitworks" class="py-12 bg-comic-dark text-white p-8 rounded-2xl comic-frame">
            <h3 class="text-4xl font-black text-center mb-16 uppercase text-comic-yellow">THE 3-STEP ROAD TO GLORY!</h3>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="flex flex-col items-center text-center p-4">
                    <div class="w-12 h-12 md:w-20 md:h-20 bg-comic-pink text-3xl md:text-5xl font-black rounded-full flex items-center justify-center mb-4 shadow-xl border-4 border-comic-yellow">1</div>
                    <h4 class="text-xl md:text-2xl font-bold mb-2 text-comic-red">SET YOUR GOAL</h4>
                    <p class="text-gray-300">Tell the AI what you want to conquer. A book? A marathon? World domination?</p>
                </div>

                <div class="hidden md:flex items-center justify-center">
                    <svg class="w-8 h-8 text-comic-yellow rotate-90 md:rotate-0 comic-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                </div>

                <div class="flex flex-col items-center text-center p-4">
                    <div class="w-12 h-12 md:w-20 md:h-20 bg-comic-red text-3xl md:text-5xl font-black rounded-full flex items-center justify-center mb-4 shadow-xl border-4 border-comic-yellow">2</div>
                    <h4 class="text-xl md:text-2xl font-bold mb-2 text-comic-pink">FACE THE PUSH</h4>
                    <p class="text-gray-300">The AI attacks procrastination with customized reminders and progress checks. Fight back!</p>
                </div>

                <div class="hidden md:flex items-center justify-center">
                    <svg class="w-8 h-8 text-comic-yellow rotate-90 md:rotate-0 comic-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                </div>

                <div class="flex flex-col items-center text-center p-4">
                    <div class="w-12 h-12 md:w-20 md:h-20 bg-comic-blue text-3xl md:text-5xl font-black rounded-full flex items-center justify-center mb-4 shadow-xl border-4 border-comic-yellow">3</div>
                    <h4 class="text-xl md:text-2xl font-bold mb-2 text-comic-yellow">ACHIEVE VICTORY!</h4>
                    <p class="text-gray-300">Complete your goal, track your success, and level up your life. You're a hero!</p>
                </div>
            </div>
        </section>

        <!-- Testimonial -->
        <section class="py-12" id="pricing">
            <h3 class="text-4xl font-black text-center mb-16 uppercase relative">
                <span class="relative z-10 bg-comic-page px-4 pb-1">What the Heroes Say</span>
                <span class="absolute w-full h-2 bg-comic-red bottom-0 left-0 transform -translate-y-1/2 z-0"></span>
            </h3>

            <div class="max-w-3xl mx-auto bg-comic-blue text-white p-8 rounded-tr-[50px] rounded-bl-[50px] shadow-comic-pop-lg comic-frame">
                <blockquote class="text-xl md:text-3xl italic font-semibold leading-snug">
                    "I used to spend three hours scrolling and twenty minutes working. Now, thanks to A.I. Coach, those numbers are reversed! It's like having a tough-but-fair drill sergeant in my pocket. 10/10!"
                </blockquote>
                <p class="mt-6 text-2xl font-extrabold text-comic-yellow">- Procrastination Pete (Now: Productive Pete!)</p>
            </div>
        </section>

        <!-- Final CTA -->
        <section class="text-center py-12">
            <h3 class="text-4xl md:text-5xl font-black mb-6 text-comic-dark">READY TO POWER UP?</h3>
            <a href="{{ url('/register') }}" class="comic-btn bg-comic-pink text-white text-3xl px-16 py-6 rounded-lg shadow-comic-button hover:bg-pink-600 inline-block">JOIN THE FIGHT!</a>
        </section>
    </main>

    <footer class="bg-comic-dark text-white mt-12 py-8 comic-frame border-b-0 border-x-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center md:flex justify-between items-center">
            <p class="text-lg font-bold mb-4 md:mb-0">&copy; 2025 A.I. COACH. All Rights Reserved. POWERED BY PIXELS.</p>
            <div class="flex justify-center space-x-6">
                <a href="#" class="text-comic-yellow hover:text-white transition duration-150 font-bold">Privacy</a>
                <a href="#" class="text-comic-yellow hover:text-white transition duration-150 font-bold">Terms</a>
                <a href="#" class="text-comic-yellow hover:text-white transition duration-150 font-bold">Contact Us</a>
            </div>
        </div>
    </footer>

</body>
</html>
