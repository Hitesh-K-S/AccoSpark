<!-- Comic Style Base (include this at top of every auth page) -->
<script src="https://cdn.tailwindcss.com"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');

    body {
        background-color: #FEF3C7; /* comic-page */
        font-family: 'Inter', sans-serif;
        color: #1F2937; /* comic-dark */
    }

    /* Comic Buttons */
    .comic-btn {
        transition: all 0.1s ease;
        border-width: 4px;
        border-style: solid;
        border-color: #1F2937;
        font-weight: 900;
        cursor: pointer;
    }
    .comic-btn:active {
        transform: translate(4px, 4px);
        box-shadow: none !important;
    }

    /* Comic Borders */
    .comic-frame {
        border-width: 4px;
        border-style: solid;
        border-color: #1F2937;
    }

    /* Comic Inputs (important fix included) */
    .comic-input {
        border: 3px solid #1F2937;
        font-weight: 700;
        color: #000 !important; /* 🔥 FIX: input text now visible */
        background-color: #fff !important;
    }

    /* Placeholder fix (visible on yellow page) */
    .comic-input::placeholder {
        color: #6B7280 !important; /* Tailwind gray-500 */
        opacity: 1;
    }

    /* Make labels readable & consistent */
    label {
        font-weight: 800;
        color: #1F2937;
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
                    'comic-green': '#10B981',
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
