<!DOCTYPE html>
<html lang="en" data-theme="black">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Design Studio | SJM</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Manrope:wght@300;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { gold: '#D4AF37', dark: '#050505', panel: '#101010' },
                    fontFamily: { serif: ['Cinzel', 'serif'], sans: ['Manrope', 'sans-serif'] }
                }
            }
        }
    </script>
</head>
<body class="bg-dark text-white font-sans min-h-screen">

    <div class="navbar fixed top-0 z-50 px-8 py-4 bg-black/80 backdrop-blur-md border-b border-white/10">
        <div class="flex-1">
            <a href="/" class="text-2xl font-serif font-bold text-white hover:text-gold transition-colors">SJM.</a>
        </div>
        <div class="flex-none gap-4">
            <span class="text-xs text-gray-400 font-mono uppercase tracking-widest">Logged in as {{ Auth::user()->username }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-ghost text-red-500">LOGOUT</button>
            </form>
        </div>
    </div>

    <div class="pt-32 pb-20 px-4 max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12">
        
        <div class="space-y-6 animate-fade-in-up">
            <div class="relative group cursor-pointer overflow-hidden rounded-2xl border border-white/10">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent z-10"></div>
                <div class="bg-panel h-[500px] flex flex-col items-center justify-center text-center p-10 relative">
                    <i class="fa-solid fa-wand-magic-sparkles text-6xl text-gold mb-6 animate-pulse"></i>
                    <h2 class="text-3xl font-serif text-white mb-2">AI Design Engine</h2>
                    <p class="text-gray-400 text-sm max-w-md">
                        Upload a sketch or describe your vision. Our AI will help visualize your masterpiece before production.
                    </p>
                    <button class="btn bg-white/10 border-white/20 text-white mt-8 hover:bg-gold hover:text-black hover:border-gold">
                        <i class="fa-solid fa-play mr-2"></i> GENERATE PREVIEW (COMING SOON)
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-panel p-8 rounded-2xl border border-white/10 shadow-2xl relative">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="fa-solid fa-gem text-9xl text-white"></i>
            </div>

            <h1 class="text-3xl font-serif text-gold mb-1">Custom Commission</h1>
            <p class="text-gray-500 text-xs uppercase tracking-widest mb-8">Submit your requirements</p>

            <form action="{{ route('custom.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label text-xs text-gray-400 font-bold tracking-widest">TYPE</label>
                        <select name="jewellery_type" class="select select-bordered bg-black border-gray-700 text-white focus:border-gold rounded-none">
                            <option disabled selected>Select Type</option>
                            <option>Ring</option>
                            <option>Necklace</option>
                            <option>Earrings</option>
                            <option>Bracelet</option>
                            <option>Watch</option>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label text-xs text-gray-400 font-bold tracking-widest">METAL</label>
                        <select name="metal_type" class="select select-bordered bg-black border-gray-700 text-white focus:border-gold rounded-none">
                            <option disabled selected>Select Material</option>
                            <option>18k Yellow Gold</option>
                            <option>18k White Gold</option>
                            <option>18k Rose Gold</option>
                            <option>Platinum</option>
                            <option>Sterling Silver</option>
                        </select>
                    </div>
                </div>

                <div class="form-control">
                    <label class="label text-xs text-gray-400 font-bold tracking-widest">ESTIMATED BUDGET ($)</label>
                    <input type="number" name="budget" placeholder="e.g. 5000" class="input input-bordered bg-black border-gray-700 text-white focus:border-gold rounded-none font-mono" />
                </div>

                <div class="form-control">
                    <label class="label text-xs text-gray-400 font-bold tracking-widest">REFERENCE IMAGE / SKETCH</label>
                    <input type="file" name="design_image" class="file-input file-input-bordered bg-black border-gray-700 text-gray-300 w-full rounded-none focus:border-gold file:bg-gold file:text-black file:border-none hover:file:bg-white" />
                    <label class="label"><span class="label-text-alt text-gray-600">Supported: JPG, PNG, PDF (Max 10MB)</span></label>
                </div>

                <div class="form-control">
                    <label class="label text-xs text-gray-400 font-bold tracking-widest">DESIGN REQUIREMENTS & NOTES</label>
                    <textarea name="description" class="textarea textarea-bordered bg-black border-gray-700 text-white focus:border-gold rounded-none h-32" placeholder="Describe gem cuts, engravings, sizing, or specific aesthetic requirements..."></textarea>
                </div>

                <button type="submit" class="btn w-full bg-gold text-black hover:bg-white border-none rounded-none font-bold tracking-widest mt-4">
                    SUBMIT REQUEST FOR REVIEW
                </button>
            </form>
        </div>
    </div>

</body>
</html>