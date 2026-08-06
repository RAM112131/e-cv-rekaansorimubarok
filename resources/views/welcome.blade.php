<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth" style="scroll-padding-top: 80px;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portofolio | {{ $user->name ?? 'Rayhan Aditya' }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Oswald:wght@400;500;700&family=Caveat&display=swap" rel="stylesheet">

    <!-- Removed global Three.js as it conflicts with React Three Fiber -->
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    
    <style>
        body {
            background-color: var(--color-dark);
            color: #ffffff;
            overflow-x: hidden;
            font-family: var(--font-sans);
        }
        
        /* Webkit Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--color-dark); }
        ::-webkit-scrollbar-thumb { background: #333; }
        ::-webkit-scrollbar-thumb:hover { background: var(--color-accentlight); }

        #three-canvas-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            pointer-events: none;
        }
        
        .glass-nav {
            background: rgba(17, 17, 24, 0.7); /* dark with opacity */
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .huge-bg-text {
            position: absolute;
            top: 5%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 20vw;
            font-family: var(--font-heading);
            font-weight: 700;
            color: var(--color-accentlight);
            opacity: 0.10;
            white-space: nowrap;
            z-index: 0;
            line-height: 1;
            user-select: none;
        }
        
        .skill-tag {
            border: 1px solid #333;
            padding: 0.35rem 1rem;
            font-size: 0.75rem;
            border-radius: 9999px;
            color: #ccc;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            background: rgba(0,0,0,0.5);
            font-family: var(--font-mono);
        }
        .skill-tag:hover {
            border-color: var(--color-accentlight);
            color: #fff;
            background: rgba(212, 175, 55, 0.1); /* Gold tint */
        }

        .timeline-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: var(--color-accentlight);
            position: absolute;
            left: -6px;
            top: 6px;
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.5); /* Gold shadow */
        }
    </style>
</head>
<body class="antialiased">

    <!-- 3D Background Canvas -->
    <div id="react-id-card-root" class="fixed inset-0 w-full z-0 opacity-100 pointer-events-auto" data-name="{{ $firstName . ' ' . $lastName }}" data-headline="{{ $user->headline }}" data-avatar="{{ $user->avatar ? Storage::url($user->avatar) : 'null' }}"></div>

    <!-- Fixed Navigation Menu -->
    <nav x-data="{ mobileMenuOpen: false, scrolled: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         :class="scrolled ? 'glass-nav' : 'bg-transparent'"
         class="fixed top-0 w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 md:px-12 py-4 flex justify-between items-center">
            <!-- Logo -->
            <a href="#beranda" class="font-heading text-2xl font-bold tracking-widest uppercase text-white">
                {{ ucfirst(strtolower(explode(' ', $user->name)[0] ?? 'Rayhan')) }}<span class="text-accentlight">.</span>
            </a>
            
            <!-- Desktop Menu -->
            <ul class="hidden md:flex gap-8 text-xs uppercase tracking-[0.15em] text-gray-400 font-medium">
                <li><a href="#beranda" class="hover:text-accentlight transition">Beranda</a></li>
                <li><a href="#skill" class="hover:text-accentlight transition">Skill</a></li>
                <li><a href="#pengalaman" class="hover:text-accentlight transition">Pengalaman</a></li>
                <li><a href="#proyek" class="hover:text-accentlight transition">Proyek</a></li>
                <li><a href="#sertifikat" class="hover:text-accentlight transition">Sertifikat</a></li>
                <li><a href="#kontak" class="hover:text-accentlight transition">Kontak</a></li>
                @auth
                    <li><a href="{{ url('/admin') }}" class="text-accentlight font-bold">Admin Panel</a></li>
                @endauth
            </ul>

            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-gray-300 focus:outline-none">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <svg x-show="mobileMenuOpen" style="display: none;" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" 
             x-transition
             style="display: none;"
             class="md:hidden bg-darker border-b border-white/10 absolute w-full left-0 top-full">
            <ul class="flex flex-col text-xs uppercase tracking-widest text-gray-400 p-6 gap-6 text-center">
                <li><a @click="mobileMenuOpen = false" href="#beranda" class="hover:text-accentlight transition block">Beranda</a></li>
                <li><a @click="mobileMenuOpen = false" href="#skill" class="hover:text-accentlight transition block">Skill</a></li>
                <li><a @click="mobileMenuOpen = false" href="#pengalaman" class="hover:text-accentlight transition block">Pengalaman</a></li>
                <li><a @click="mobileMenuOpen = false" href="#proyek" class="hover:text-accentlight transition block">Proyek</a></li>
                <li><a @click="mobileMenuOpen = false" href="#sertifikat" class="hover:text-accentlight transition block">Sertifikat</a></li>
                <li><a @click="mobileMenuOpen = false" href="#kontak" class="hover:text-accentlight transition block">Kontak</a></li>
                @auth
                    <li><a @click="mobileMenuOpen = false" href="{{ url('/admin') }}" class="text-accentlight font-bold block">Admin Panel</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    <!-- SECTION: BERANDA -->
    <header id="beranda" class="relative min-h-screen flex flex-col justify-center px-6 md:px-12 pt-24 pb-20 overflow-hidden pointer-events-none">
        <!-- Huge Background Text -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-0">
            <h1 class="font-heading font-bold text-[20vw] text-accent opacity-10 leading-none whitespace-nowrap select-none" style="transform: translateY(-5%);">PORTFOLIO</h1>
        </div>
        
        <div class="relative z-10 w-full mt-0.5 max-w-7xl mx-auto flex flex-col lg:flex-row items-center justify-between mt-10 pointer-events-none">
            <div class="w-full lg:w-3/5 flex flex-col pointer-events-auto">

                <span style="font-family: var(--font-script);" class="text-white text-3xl md:text-5xl mb-2">Hello, I'm</span>
                <h1 class="font-heading text-6xl md:text-7xl lg:text-[6rem] font-bold leading-[1.0] tracking-tight mb-0 text-white uppercase">
                    {{ $firstName }}
                </h1>
                <h1 class="font-heading text-6xl md:text-7xl lg:text-[6rem] font-bold leading-[1.0] tracking-tight mb-4 text-white uppercase">
                    {{ $lastName }}
                </h1>

                @if($user->headline)
                <h2 class="text-accentlight font-sans font-bold tracking-[0.2em] uppercase text-sm md:text-base mb-6">
                    {{ $user->headline }}
                </h2>
                @endif

                <p class="text-gray-400 text-sm md:text-[15px] leading-[1.8] mb-8 max-w-md font-sans font-light">
                    {{ $user->bio ?? 'I design and build stylish, user-focused web experiences that combine creativity with strategy. Passionate about clean design, smooth interactions, and details that make a difference.' }}
                </p>

                <!-- Action Buttons & Socials -->
                <div class="flex flex-wrap items-center gap-6 mb-12">
                    <!-- Download CV Button -->
                    @if(Route::has('cv.download'))
                    <a href="{{ route('cv.download') }}" class="group relative px-6 py-3 border border-accentlight text-accentlight font-sans font-bold uppercase tracking-widest text-xs hover:bg-accentlight hover:text-darker transition-all duration-300 rounded-sm overflow-hidden">
                        <span class="relative z-10 flex items-center gap-2">
                            <span>Download CV</span>
                            <svg class="w-4 h-4 transform group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </span>
                    </a>
                    @else
                    <a href="{{ $user->cv_file ? Storage::url($user->cv_file) : '#' }}" target="_blank" class="group relative px-6 py-3 border border-accentlight text-accentlight font-sans font-bold uppercase tracking-widest text-xs hover:bg-accentlight hover:text-darker transition-all duration-300 rounded-sm overflow-hidden">
                        <span class="relative z-10 flex items-center gap-2">
                            <span>Download CV</span>
                            <svg class="w-4 h-4 transform group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </span>
                    </a>
                    @endif

                    <!-- Divider -->
                    @if(isset($user) && $user->socialLinks->count() > 0)
                    <div class="hidden md:block w-px h-8 bg-white/10"></div>

                    <!-- Social Links -->
                    <div class="flex items-center gap-4">
                        @foreach($user->socialLinks as $link)
                        <a href="{{ $link->url }}" target="_blank" class="text-gray-400 hover:text-accentlight transform hover:-translate-y-1 transition-all duration-300" title="{{ $link->platform }}">
                            @if(strtolower($link->platform) == 'github')
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"></path></svg>
                            @elseif(strtolower($link->platform) == 'linkedin')
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd"></path></svg>
                            @elseif(strtolower($link->platform) == 'instagram')
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" clip-rule="evenodd"></path></svg>
                            @elseif(strtolower($link->platform) == 'twitter' || strtolower($link->platform) == 'x')
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path></svg>
                            @else
                            <span class="font-bold text-xs uppercase tracking-widest">{{ $link->platform }}</span>
                            @endif
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
                
                <div class="flex items-center gap-3 text-gray-500 text-[10px] md:text-xs font-mono tracking-widest uppercase">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ $user->location ? strtoupper($user->location) : 'AVAILABLE FOR FREELANCE' }}
                </div>
            </div>
            
            <div class="hidden lg:flex flex-col gap-10 text-left absolute right-0">
                <div class="flex items-center gap-4">
                    <h3 class="text-accentlight font-heading text-5xl md:text-7xl font-bold mb-0">{{ $yearsExperience }}+</h3>
                    <p class="text-gray-400 text-[10px] font-sans font-bold uppercase tracking-widest leading-relaxed">YEARS<br>EXPERIENCE</p>
                </div>
                <div class="flex items-center gap-4">
                    <h3 class="text-accentlight font-heading text-5xl md:text-7xl font-bold mb-0">{{ $totalProjects }}+</h3>
                    <p class="text-gray-400 text-[10px] font-sans font-bold uppercase tracking-widest leading-relaxed">PROJECTS<br>COMPLETED</p>
                </div>
                <div class="flex items-center gap-4">
                    <h3 class="text-accentlight font-heading text-5xl md:text-7xl font-bold mb-0">{{ $totalClients }}+</h3>
                    <p class="text-gray-400 text-[10px] font-sans font-bold uppercase tracking-widest leading-relaxed">HAPPY<br>CLIENTS</p>
                </div>
            </div>
        </div>
    </header>

    <!-- SECTION: SKILL -->
    <section id="skill" class="px-6 md:px-12 py-24 bg-darker/80 border-t border-white/5 relative z-10">
        <div class="max-w-7xl mx-auto">
            <h3 class="font-heading text-3xl tracking-wide mb-12 flex items-center gap-4 text-white">
                <span class="text-accentlight text-4xl leading-none font-sans font-light">+</span> Keahlian & Teknologi
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                @foreach($groupedSkills as $category => $categorySkills)
                <div>
                    <h4 class="text-accentlight text-xs uppercase tracking-[0.2em] mb-6 pb-2 border-b border-white/10">{{ $category }}</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($categorySkills as $skill)
                            <span class="skill-tag">{{ $skill->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- SECTION: PENGALAMAN -->
    <section id="pengalaman" class="px-6 md:px-12 py-24 bg-dark/90 border-t border-white/5 relative z-10">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16">
            <div>
                <h3 class="font-heading text-3xl tracking-wide mb-12 flex items-center gap-4 text-white">
                    <span class="text-accentlight text-4xl leading-none font-sans font-light">+</span> Pengalaman
                </h3>
                <p class="text-gray-400 text-[14px] leading-[1.75] mb-8">
                    Fokus saya bukan hanya pada penulisan kode, tetapi bagaimana teknologi dapat memberikan solusi nyata. Berikut adalah perjalanan kolaborasi dan implementasi sistem yang pernah saya lakukan.
                </p>
                
                <!-- Timeline -->
                <div class="relative border-l border-white/10 ml-2 pl-8 flex flex-col gap-10 mt-10">
                    @foreach($experiences as $exp)
                    <div class="relative">
                        <div class="timeline-dot"></div>
                        <span class="text-[10px] text-accentlight font-sans tracking-[0.1em] mb-1 block uppercase font-medium">
                            {{ \Carbon\Carbon::parse($exp->start_date)->format('Y') }} - {{ $exp->is_current ? 'Sekarang' : \Carbon\Carbon::parse($exp->end_date)->format('Y') }}
                        </span>
                        <h5 class="text-lg font-medium text-white mb-1 tracking-wide">{{ $exp->title }}</h5>
                        <h6 class="text-[10px] text-gray-500 uppercase tracking-[0.1em] mb-3">{{ $exp->company }}</h6>
                        <p class="text-[14px] text-gray-400 leading-[1.75]">
                            {{ $exp->description }}
                        </p>
                    </div>
                    @endforeach

                </div>
            </div>
            
            <!-- Visual / Quote area for Experience -->
            <div class="flex items-center justify-center">
                <div class="w-full aspect-[4/5] bg-[#14141c] border border-white/5 p-10 flex flex-col justify-center rounded-sm relative overflow-hidden backdrop-blur-sm">
                    <div class="absolute -bottom-10 -right-10 text-[12rem] opacity-5 font-serif leading-none text-white">"</div>
                    <div class="text-4xl mb-4 font-serif text-accentlight">"</div>
                    <p class="text-xl md:text-2xl font-heading italic leading-[1.5] mb-8 relative z-10 text-gray-200">
                        Teknologi terbaik adalah yang mampu menjembatani kebutuhan manusia dan menciptakan dampak positif pada lingkungan sekitarnya.
                    </p>
                    <div class="font-heading text-3xl text-gray-400">Rayhan</div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION: PROYEK -->
    <section id="proyek" class="px-6 md:px-12 py-24 bg-darker border-t border-white/5 relative z-10">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between md:items-end mb-12">
            <h3 class="font-heading text-3xl tracking-wide flex items-center gap-4 text-white">
                <span class="text-accentlight text-4xl leading-none font-sans font-light">+</span> Pilihan Proyek
            </h3>
        </div>
        
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
            
            @foreach($projects as $index => $project)
            <div class="group cursor-pointer" onclick="window.open('{{ $project->link ?? '#' }}', '_blank')">
                <div class="w-full aspect-[16/9] bg-[#0d0d12] border border-white/5 rounded-sm overflow-hidden mb-6 relative flex items-center justify-center">
                    @if($project->image)
                        <img src="{{ Storage::url($project->image) }}" alt="{{ $project->title }}" class="absolute inset-0 w-full h-full object-cover opacity-30 group-hover:opacity-60 transition duration-500">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-darker to-transparent z-10"></div>
                    <h2 class="absolute bottom-6 left-6 z-20 font-heading text-4xl font-bold opacity-10 group-hover:opacity-20 transition duration-500 text-white uppercase">{{ Str::slug($project->title, ' ') }}</h2>
                    <!-- Placeholder visual for project -->
                    <div class="w-32 h-32 border-2 border-accentlight/30 rounded-full flex items-center justify-center group-hover:scale-110 transition duration-500 z-20">
                        <svg class="w-12 h-12 text-accentlight" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    </div>
                </div>
                <div class="flex items-start gap-4 px-2">
                    <span class="font-heading text-3xl text-accentlight">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <div>
                        <h4 class="font-heading text-xl tracking-wider mb-1 text-white">{{ $project->title }}</h4>
                        <p class="text-[10px] text-gray-500 uppercase tracking-[0.12em] mb-3 font-medium">{{ $project->category ?? 'Proyek' }}</p>
                        <p class="text-[14px] leading-[1.75] text-gray-400">{{ Str::limit($project->description, 120) }}</p>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </section>

    <!-- SECTION: SERTIFIKAT -->
    <section id="sertifikat" class="px-6 md:px-12 py-24 bg-dark/90 border-t border-white/5 relative z-10">
        <div class="max-w-7xl mx-auto">
            <h3 class="font-heading text-3xl tracking-wide mb-12 flex items-center gap-4 text-white">
                <span class="text-accentlight text-4xl leading-none font-sans font-light">+</span> Sertifikat & Edukasi
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($certificates as $cert)
                <div class="bg-darker border border-white/5 p-8 hover:border-accentlight/50 transition rounded-sm flex flex-col h-full" @if($cert->url) onclick="window.open('{{ $cert->url }}', '_blank')" style="cursor: pointer;" @endif>
                    <div class="text-accentlight mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    </div>
                    <h4 class="font-heading text-xl tracking-wide mb-2 text-white">{{ $cert->title }}</h4>
                    <p class="text-[10px] text-gray-500 uppercase tracking-[0.1em] mb-4 font-medium">{{ $cert->issuer }} &bull; {{ \Carbon\Carbon::parse($cert->issue_date)->format('M Y') }}</p>
                    <p class="text-[14px] leading-[1.75] text-gray-400 grow">{{ $cert->description }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- SECTION: KONTAK (Footer) -->
    <footer id="kontak" class="px-6 md:px-12 py-24 bg-darker border-t border-white/5 relative z-10">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start gap-12">
            
            <!-- Left Info -->
            <div class="max-w-md">
                <h2 class="font-heading text-5xl md:text-6xl font-light leading-none tracking-tight mb-6 text-white">
                    Mari Bekerja Sama <span class="text-accentlight text-4xl align-top font-sans font-light">+</span>
                </h2>
                <p class="text-gray-400 text-[14px] leading-[1.75] mb-12">
                    Saya selalu terbuka untuk mendiskusikan peluang proyek web development, desain UI/UX, maupun analisis data. Mari ciptakan sebuah produk digital yang memukau dan fungsional bersama.
                </p>
                
                <div class="flex items-center gap-4 text-[11px] uppercase tracking-[0.1em] text-accentlight font-medium">
                    <div class="w-10 h-10 rounded-full border border-accentlight/30 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </div>
                    Tersedia Untuk Magang
                </div>
            </div>

            <!-- Middle Contacts -->
            <div class="flex flex-col gap-8 pt-4">
                <a href="mailto:{{ $user->email ?? 'hello@example.com' }}" class="flex items-center gap-6 text-gray-300 hover:text-white transition group">
                    <div class="w-12 h-12 rounded-full border border-white/10 flex items-center justify-center group-hover:border-accentlight group-hover:bg-accentlight/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-500 uppercase tracking-[0.1em] mb-1 font-medium">Email</span>
                        <span class="text-[14px] tracking-wide text-white">{{ $user->email ?? 'hello@example.com' }}</span>
                    </div>
                </a>
                
                <a href="{{ $user->linkedin_url ?? '#' }}" target="_blank" class="flex items-center gap-6 text-gray-300 group cursor-pointer hover:text-white transition">
                    <div class="w-12 h-12 rounded-full border border-white/10 flex items-center justify-center group-hover:border-accentlight group-hover:bg-accentlight/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"></path><circle cx="4" cy="4" r="2" stroke="none" fill="currentColor"></circle></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-500 uppercase tracking-[0.1em] mb-1 font-medium">LinkedIn</span>
                        <span class="text-[14px] tracking-wide text-white">Hubungi di LinkedIn</span>
                    </div>
                </a>

                <div class="flex items-center gap-6 text-gray-300">
                    <div class="w-12 h-12 rounded-full border border-white/10 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-500 uppercase tracking-[0.1em] mb-1 font-medium">Lokasi</span>
                        <span class="text-[14px] tracking-wide text-white">{{ $user->location ?? 'Indonesia' }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto mt-20 pt-8 border-t border-white/5 flex justify-between items-center text-[10px] text-gray-600 uppercase tracking-[0.1em] font-medium">
            <p>&copy; 2026 Rayhan Aditya. All Rights Reserved.</p>
            <p>Built with Tailwind & Three.js</p>
        </div>
    </footer>


</body>
</html>
