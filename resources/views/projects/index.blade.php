<!DOCTYPE html>
<html lang="id" class="scroll-smooth bg-darker">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Proyek — {{ $user->name ?? 'Portfolio' }}</title>
    <meta name="description" content="Kumpulan proyek web development, UI/UX, dan data analysis yang telah saya bangun. Jelajahi seluruh portfolio karya {{ $user->name ?? '' }}.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('projects.index') }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="Semua Proyek — {{ $user->name ?? 'Portfolio' }}">
    <meta property="og:description" content="Jelajahi seluruh portfolio proyek web development dan desain.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('projects.index') }}">

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/app.jsx'])

    <style>
        [x-cloak] { display: none !important; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #010010; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #d4af37; }

        .glass-nav {
            background: rgba(3, 0, 20, 0.8);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-bottom: 1px solid rgba(0, 212, 255, 0.1);
        }

        /* Nav link active/hover underline glow */
        .nav-link-futuristic {
            position: relative;
            padding-bottom: 4px;
        }
        .nav-link-futuristic::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: linear-gradient(90deg, #d4af37, #00d4ff);
            box-shadow: 0 0 6px rgba(212, 175, 55, 0.3);
            transition: width 0.3s ease;
        }
        .nav-link-futuristic:hover::after,
        .nav-link-futuristic.active::after {
            width: 100%;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .card-anim { animation: fadeUp 0.45s ease both; }
    </style>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-darker text-white font-sans antialiased overflow-x-hidden selection:bg-amber-400/30 selection:text-white min-h-screen flex flex-col"
      x-data="{ 
          activeCategory: 'SEMUA',
          search: '',
          sortOrder: 'terbaru',
          categories: {{ json_encode($projects->pluck('category')->filter()->unique()->values()) }},
          filterProject(title, cat, desc) {
              if (this.activeCategory !== 'SEMUA') {
                  if (!cat || !cat.toUpperCase().includes(this.activeCategory.toUpperCase())) return false;
              }
              if (this.search.trim() !== '') {
                  let q = this.search.toLowerCase();
                  let matchTitle = (title || '').toLowerCase().includes(q);
                  let matchCat = (cat || '').toLowerCase().includes(q);
                  let matchDesc = (desc || '').toLowerCase().includes(q);
                  if (!matchTitle && !matchCat && !matchDesc) return false;
              }
              return true;
          }
      }">

    <!-- Swarm Cursor Root -->
    <div id="react-swarm-cursor-root" class="fixed inset-0 z-0 pointer-events-none"></div>

    {{-- ══════════════════════════════════════════
         NAVBAR — identical to welcome.blade.php
    ══════════════════════════════════════════ --}}
    <!-- Fixed Navigation Menu -->
    <div class="fixed top-0 left-0 right-0 z-50 flex justify-center p-4 transition-all duration-300">
        <nav x-data="{ mobileMenuOpen: false, scrolled: false }" 
             @scroll.window="scrolled = (window.pageYOffset > 20)"
             :class="scrolled ? 'glass-nav py-3 px-8 rounded-full shadow-2xl max-w-6xl w-full' : 'bg-transparent py-5 px-4 w-full'"
             class="transition-all duration-300 flex justify-between items-center max-w-7xl mx-auto">
            <!-- Logo -->
            <a href="{{ route('home') }}#beranda" class="font-heading text-2xl font-bold tracking-widest uppercase text-white cursor-pointer hover:opacity-85 transition">
                {{ ucfirst(strtolower(explode(' ', $user->name ?? '')[0] ?? 'REKA')) }}<span class="text-accentlight" style="text-shadow: 0 0 8px rgba(212,175,55,0.4);">.</span>
            </a>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-8">
                <ul class="flex gap-8 text-xs uppercase tracking-[0.15em] text-gray-400 font-medium items-center">
                    <li><a href="{{ route('home') }}#beranda" class="nav-link-futuristic hover:text-accentlight transition duration-200 cursor-pointer">Beranda</a></li>
                    <li><a href="{{ route('projects.index') }}" class="nav-link-futuristic text-accentlight font-bold active cursor-pointer">Proyek</a></li>
                    <li><a href="{{ route('certificates.index') }}" class="nav-link-futuristic hover:text-accentlight transition duration-200 cursor-pointer">Sertifikat</a></li>
                    <li><a href="{{ route('home') }}#kontak" class="nav-link-futuristic hover:text-accentlight transition duration-200 cursor-pointer">Kontak</a></li>
                    @auth
                        <li><a href="{{ url('/admin') }}" class="text-accentlight font-bold hover:text-accent transition duration-200 cursor-pointer">Admin Panel</a></li>
                    @endauth
                </ul>
                
                <!-- Download CV Button (Clay) -->
                @if(Route::has('cv.download'))
                <a href="{{ route('cv.download') }}" class="clay-btn bg-accent/90 hover:bg-accentlight text-slate-950 font-sans font-bold uppercase tracking-wider text-xs px-5 py-2.5 inline-flex items-center gap-2 cursor-pointer">
                    <span>Download CV</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </a>
                @endif
            </div>

            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-gray-300 focus:outline-none cursor-pointer">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <svg x-show="mobileMenuOpen" style="display: none;" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            
            <!-- Mobile Menu Dropdown -->
            <div x-show="mobileMenuOpen"
                 x-transition
                 style="display: none;"
                 class="md:hidden clay-card absolute left-4 right-4 top-full mt-2 shadow-2xl z-50">
                <ul class="flex flex-col text-xs uppercase tracking-widest text-gray-400 p-6 gap-6 text-center">
                    <li><a @click="mobileMenuOpen = false" href="{{ route('home') }}#beranda" class="hover:text-accentlight transition block cursor-pointer">Beranda</a></li>
                    <li><a @click="mobileMenuOpen = false" href="{{ route('projects.index') }}" class="text-accentlight font-bold transition block cursor-pointer">Proyek</a></li>
                    <li><a @click="mobileMenuOpen = false" href="{{ route('certificates.index') }}" class="hover:text-accentlight transition block cursor-pointer">Sertifikat</a></li>
                    <li><a @click="mobileMenuOpen = false" href="{{ route('home') }}#kontak" class="hover:text-accentlight transition block cursor-pointer">Kontak</a></li>
                    @auth
                        <li><a @click="mobileMenuOpen = false" href="{{ url('/admin') }}" class="text-accentlight font-bold block cursor-pointer">Admin Panel</a></li>
                    @endauth
                    @if(Route::has('cv.download'))
                    <li>
                        <a @click="mobileMenuOpen = false" href="{{ route('cv.download') }}" class="clay-btn bg-accent/90 text-slate-950 font-bold uppercase tracking-wider text-xs px-5 py-2.5 inline-flex items-center justify-center gap-2 cursor-pointer mx-auto">
                            <span>Download CV</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </nav>
    </div>

    {{-- ══════════════════════════════════════════
         MAIN CONTENT — FILTER BAR + 3-COLUMN GRID
    ══════════════════════════════════════════ --}}
    <main id="projects-grid" class="flex-grow relative z-10 pt-28 sm:pt-32 px-6 md:px-12 pb-24">
        <div class="max-w-7xl mx-auto">

            <!-- Subheader & Filter Control Header -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8">
                <div>
                    <h3 class="font-heading text-2xl sm:text-3xl text-white font-bold tracking-tight mb-1">
                        Semua Proyek
                    </h3>
                    <p class="text-slate-400 font-sans text-xs">Jelajahi dan filter daftar proyek digital yang telah dibangun.</p>
                </div>

                <!-- Right Controls: Search Input, Kategori Filter, & Sort Order (1 Desktop Line) -->
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto lg:flex-nowrap shrink-0">
                    <!-- Live Search Input -->
                    <div class="relative w-full sm:w-52 lg:w-56 shrink-0">
                        <input type="text" x-model="search" placeholder="Cari proyek..."
                               class="w-full bg-[#080a15] border border-amber-400/20 focus:border-amber-400 rounded-xl px-4 py-2 pl-10 text-xs font-mono text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-amber-400 transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)]">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <!-- Filter Kategori Select Dropdown -->
                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end shrink-0">
                        <span class="text-xs font-mono text-slate-400 whitespace-nowrap">Kategori:</span>
                        <select x-model="activeCategory" class="bg-[#080a15] border border-amber-400/30 text-xs font-mono text-amber-300 rounded-xl px-3.5 py-2 focus:outline-none focus:border-amber-400 cursor-pointer shadow-[inset_0_1px_3px_rgba(255,255,255,0.05),0_4px_10px_rgba(0,0,0,0.4)]">
                            <option value="SEMUA">Semua Kategori</option>
                            <template x-for="cat in categories" :key="cat">
                                <option :value="cat" x-text="cat"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Urutkan Select Dropdown -->
                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end shrink-0">
                        <span class="text-xs font-mono text-slate-400 whitespace-nowrap">Urutkan:</span>
                        <select x-model="sortOrder" class="bg-[#080a15] border border-amber-400/30 text-xs font-mono text-amber-300 rounded-xl px-3.5 py-2 focus:outline-none focus:border-amber-400 cursor-pointer shadow-[inset_0_1px_3px_rgba(255,255,255,0.05),0_4px_10px_rgba(0,0,0,0.4)]">
                            <option value="terbaru">Terbaru</option>
                            <option value="terlama">Terlama</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Projects Grid — 3 Columns Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                @forelse($projects as $i => $project)
                <div x-show="filterProject('{{ addslashes($project->title) }}', '{{ addslashes($project->category) }}', '{{ addslashes($project->description ?? '') }}')"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="card-anim"
                     style="animation-delay: {{ ($i % 9) * 50 }}ms">
                    
                    <a href="{{ route('project.show', $project->slug) }}"
                       class="group relative clay-card p-5 sm:p-6 rounded-2xl bg-[#0a0d18]/70 border border-amber-400/30 hover:border-amber-400/70 transition-all duration-500 hover:-translate-y-2.5 hover:shadow-[0_0_30px_rgba(212,175,55,0.25)] cursor-pointer flex flex-col justify-between h-full overflow-hidden block relative z-10 backdrop-blur-md">
                        
                        @if($project->image)
                            <!-- Visible Project Cover Image Card Background Layer -->
                            <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none rounded-2xl">
                                <img src="{{ Storage::disk('public')->url($project->image) }}" alt="" 
                                     class="w-full h-full object-cover scale-105 group-hover:scale-110 opacity-55 group-hover:opacity-75 transition-all duration-700 select-none pointer-events-none"
                                     oncontextmenu="return false;"
                                     ondragstart="return false;"
                                     draggable="false">
                                <!-- Vignette Gradients: Darker on left & bottom for crisp text readability -->
                                <div class="absolute inset-0 bg-gradient-to-r from-[#060815]/95 via-[#060815]/75 to-[#060815]/25"></div>
                                <div class="absolute inset-0 bg-gradient-to-t from-[#060815]/95 via-transparent to-[#060815]/30"></div>
                            </div>
                        @endif

                        <!-- Ambient Glowing Orbs Background (Pure Gold Theme matching Hero) -->
                        <div class="absolute -top-14 -right-14 w-48 h-48 bg-amber-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-amber-500/30 transition-all duration-700 z-0"></div>
                        <div class="absolute -bottom-14 -left-14 w-48 h-48 bg-amber-500/15 rounded-full blur-3xl pointer-events-none group-hover:bg-amber-500/25 transition-all duration-700 z-0"></div>
                        
                        <!-- Futuristic Grid Pattern Watermark Overlay -->
                        <div class="absolute inset-0 bg-[radial-gradient(#d4af37_1px,transparent_1px)] [background-size:20px_20px] opacity-[0.05] group-hover:opacity-[0.1] transition-opacity duration-500 pointer-events-none z-0"></div>

                        <div class="relative z-10">
                            <!-- Top Image Box Container -->
                            <div class="clay-card w-full h-48 rounded-2xl overflow-hidden border border-amber-400/30 relative mb-4 bg-[#070912] shadow-[0_10px_30px_rgba(0,0,0,0.8),inset_0_1px_3px_rgba(255,255,255,0.1)] group-hover:border-amber-400/60 transition-colors">
                                @if($project->image)
                                    <img src="{{ Storage::disk('public')->url($project->image) }}"
                                         alt="{{ $project->title }}"
                                         class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700 ease-out select-none pointer-events-none"
                                         loading="lazy"
                                         oncontextmenu="return false;"
                                         ondragstart="return false;"
                                         draggable="false">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-600 font-mono text-xs">
                                        [ No Image ]
                                    </div>
                                @endif
                                
                                <!-- Top Bar Overlay: Category Tag Pill (Clay Card matching Hero) -->
                                <div class="absolute top-3 inset-x-3 flex items-center justify-between z-10">
                                    <span class="clay-card inline-flex items-center gap-1.5 px-3 py-1 rounded-full border border-amber-400/40 text-amber-300 font-mono text-[10px] font-bold uppercase tracking-widest shadow-lg">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                        {{ $project->category }}
                                    </span>
                                </div>
                            </div>

                            <!-- Tech Stack Badges -->
                            <div class="flex flex-wrap gap-1.5 mb-3">
                                @php
                                    $stacks = $project->tech_stack ? array_map('trim', explode(',', $project->tech_stack)) : [];
                                    $displayStacks = array_slice($stacks, 0, 3);
                                @endphp
                                @foreach($displayStacks as $stack)
                                    <span class="clay-card px-2.5 py-0.5 rounded-full border border-amber-400/20 text-[10px] font-mono text-slate-300 inline-flex items-center gap-1 shadow-sm group-hover:border-amber-400/40 transition-colors">
                                        <span class="w-1 h-1 rounded-full bg-amber-400/80"></span>
                                        {{ $stack }}
                                    </span>
                                @endforeach
                                @if(count($stacks) > 3)
                                    <span class="clay-card px-2 py-0.5 rounded-full border border-slate-800 text-[10px] font-mono text-slate-400">
                                        +{{ count($stacks) - 3 }}
                                    </span>
                                @endif
                            </div>

                            <!-- Title & Description -->
                            <h3 class="font-heading font-bold text-xl text-white group-hover:text-amber-300 transition-colors mb-1.5 line-clamp-1">
                                {{ $project->title }}
                            </h3>
                            <p class="text-slate-400 text-xs font-sans leading-relaxed line-clamp-2 mb-4">
                                {{ $project->description }}
                            </p>
                        </div>

                        <!-- Bottom Link CTA -->
                        <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between relative z-10">
                            <span class="text-xs font-mono font-bold text-amber-300 group-hover:text-amber-400 flex items-center gap-1.5 transition-colors neon-text-gold">
                                <span>LIHAT DETAIL</span>
                                <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </span>
                        </div>

                    </a>
                </div>
                @empty
                <div class="col-span-3 py-24 text-center">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center">
                        <svg class="w-7 h-7 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-slate-500 font-mono text-sm">Belum ada proyek yang dipublikasikan.</p>
                </div>
                @endforelse
            </div>

            <!-- Load More Pill Button ("MUAT LEBIH BANYAK v") -->
            <div class="mt-16 text-center">
                <button @click="visibleCount += 6"
                        class="group clay-btn bg-accent hover:bg-accentlight text-slate-950 font-sans font-bold uppercase tracking-wider text-xs px-6 py-3.5 cursor-pointer overflow-hidden inline-flex items-center gap-2.5 shadow-lg">
                    <span>MUAT LEBIH BANYAK</span>
                    <svg class="w-4 h-4 text-slate-950 transform group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>

            <!-- Bottom Full Quote Clay Card -->
            <div class="mt-20 p-6 rounded-2xl bg-[#090c13] border border-slate-800/80 shadow-[inset_0_1px_1px_rgba(255,255,255,0.04),0_10px_25px_rgba(0,0,0,0.5)] relative overflow-hidden flex items-center justify-between gap-6">
                <div class="flex items-center gap-4 relative z-10">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 font-serif text-2xl font-bold flex-shrink-0">
                        “
                    </div>
                    <p class="text-slate-300 text-sm italic font-sans">
                        Setiap proyek adalah kesempatan untuk menciptakan sesuatu yang bermakna.
                    </p>
                </div>

                <!-- 3D Orbit Sphere Graphic on right -->
                <div class="w-28 h-12 pointer-events-none opacity-30 hidden md:flex items-center justify-center flex-shrink-0 relative z-0 text-amber-400">
                    <svg viewBox="0 0 100 40" fill="none" class="w-full h-full">
                        <circle cx="50" cy="20" r="14" fill="currentColor" fill-opacity="0.15" stroke="currentColor" stroke-width="1.2" />
                        <ellipse cx="50" cy="20" rx="35" ry="10" stroke="currentColor" stroke-width="1.2" transform="rotate(-15 50 20)" />
                        <circle cx="75" cy="12" r="3" fill="currentColor" />
                    </svg>
                </div>
            </div>

        </div>
    </main>

    {{-- ══════════════════════════════════════════
         FOOTER
    ══════════════════════════════════════════ --}}
    <footer class="relative z-10 border-t border-slate-900 py-10 px-6 md:px-12 bg-darker">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-slate-600 text-[11px] font-mono uppercase tracking-widest">
                &copy; {{ date('Y') }} {{ $user->name ?? 'Portfolio' }}. All rights reserved.
            </p>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}#kontak" class="text-slate-500 hover:text-amber-300 text-[11px] font-mono uppercase tracking-widest transition-colors">
                    Hubungi Saya
                </a>
                <span class="text-slate-800">·</span>
                <a href="{{ route('home') }}" class="text-slate-500 hover:text-white text-[11px] font-mono uppercase tracking-widest transition-colors">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </footer>

</body>
</html>
