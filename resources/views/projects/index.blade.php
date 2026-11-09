<!DOCTYPE html>
<html lang="id" class="scroll-smooth bg-darker dark">
<head>
    <x-theme-preference />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('components.navigation-prefetch')
    <title>Semua Proyek — {{ $user->name ?? 'Portfolio' }}</title>
    <meta name="description" content="Kumpulan proyek web development, UI/UX, dan data analysis yang telah saya bangun. Jelajahi seluruh portfolio karya {{ $user->name ?? '' }}.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('projects.index') }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="Semua Proyek — {{ $user->name ?? 'Portfolio' }}">
    <meta property="og:description" content="Jelajahi seluruh portfolio proyek web development dan desain.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('projects.index') }}">

    @vite('resources/css/app.css')

    <style>
        [x-cloak] { display: none !important; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #010010; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #d4af37; }

    </style>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
</head>
<body class="bg-darker text-white font-sans antialiased overflow-x-hidden selection:bg-amber-400/30 selection:text-white min-h-screen flex flex-col"
      x-data="{ 
          activeCategory: 'SEMUA',
          search: '',
          sortOrder: 'terbaru',
          visibleCount: 6,
          filteredProjectCount: 0,
          categories: {{ json_encode($projects->pluck('category')->filter()->unique()->values()) }},
          filterProject(title, cat, desc, techStack) {
              if (this.activeCategory !== 'SEMUA') {
                  if (!cat || !cat.toUpperCase().includes(this.activeCategory.toUpperCase())) return false;
              }
              if (this.search.trim() !== '') {
                  const query = this.search.toLocaleLowerCase('id');
                  const searchableText = [title, cat, desc, techStack]
                      .filter(Boolean)
                      .join(' ')
                      .toLocaleLowerCase('id');

                  if (!searchableText.includes(query)) {
                      return false;
                  }
              }
              return true;
          },
          refreshProjects() {
              const cards = Array.from(this.$refs.projectGrid.querySelectorAll('[data-project-card]'));
              const direction = this.sortOrder === 'terlama' ? 1 : -1;
              const matchingCards = cards.filter((card) =>
                  this.filterProject(
                      card.dataset.title,
                      card.dataset.category,
                      card.dataset.description,
                      card.dataset.techStack
                  )
              ).sort((first, second) => {
                  const dateDifference = Number(first.dataset.createdAt) - Number(second.dataset.createdAt);

                  return dateDifference === 0
                      ? (Number(first.dataset.projectOrder) - Number(second.dataset.projectOrder)) * direction
                      : dateDifference * direction;
              });

              cards.forEach((card) => {
                  card.hidden = true;
              });
              matchingCards.forEach((card, index) => {
                  card.style.order = index;
                  card.hidden = index >= this.visibleCount;
              });
              this.filteredProjectCount = matchingCards.length;
          }
      }">

    <!-- Swarm Cursor Root -->

    {{-- ══════════════════════════════════════════
         NAVBAR — identical to welcome.blade.php
    ══════════════════════════════════════════ --}}
    <!-- Fixed Navigation Menu -->
    <div class="fixed top-0 left-0 right-0 z-50 flex justify-center p-4 transition-all duration-300">
        <nav x-data="{ 
                 mobileMenuOpen: false, 
                 scrolled: false
             }"
             x-effect="document.body.style.overflow = mobileMenuOpen ? 'hidden' : ''"
             @scroll.window="if (!mobileMenuOpen) scrolled = (window.pageYOffset > 20)"
             :class="scrolled ? 'glass-nav py-2.5 sm:py-3 px-4 sm:px-8 rounded-full shadow-2xl max-w-6xl w-full' : 'bg-transparent py-4 sm:py-5 px-4 w-full'"
             class="transition-all duration-300 flex justify-between items-center max-w-7xl mx-auto">
            <!-- Logo -->
            <a href="{{ route('home') }}#beranda" class="font-heading text-xl sm:text-2xl font-bold tracking-widest uppercase text-white cursor-pointer hover:opacity-85 transition shrink-0">
                {{ ucfirst(strtolower(explode(' ', $user->name ?? '')[0] ?? 'REKA')) }}<span class="text-accentlight" style="text-shadow: 0 0 8px rgba(212,175,55,0.4);">.</span>
            </a>
            
            <!-- Right Container: Desktop Links + Actions -->
            <div class="flex items-center gap-6 sm:gap-8 ml-auto">
                <!-- Desktop Links (Right-aligned next to Download CV) -->
                <ul class="hidden md:flex gap-6 lg:gap-8 text-xs uppercase tracking-[0.15em] text-gray-400 font-medium items-center">
                    <li><a href="{{ route('home') }}#beranda" class="nav-link-futuristic hover:text-accentlight transition duration-200 cursor-pointer">Beranda</a></li>
                    <li><a href="{{ route('projects.index') }}" class="nav-link-futuristic text-accentlight font-bold active cursor-pointer">Proyek</a></li>
                    <li><a href="{{ route('certificates.index') }}" class="nav-link-futuristic hover:text-accentlight transition duration-200 cursor-pointer">Sertifikat</a></li>
                    <li><a href="{{ route('home') }}#kontak" class="nav-link-futuristic hover:text-accentlight transition duration-200 cursor-pointer">Kontak</a></li>
                    @auth
                        <li><a href="{{ url('/admin') }}" class="text-accentlight font-bold hover:text-accent transition duration-200 cursor-pointer">Admin Panel</a></li>
                    @endauth
                </ul>
                
                <!-- Actions (Download CV + Theme Toggle + Mobile Hamburger) -->
                <div class="flex items-center gap-2 sm:gap-3">
                    @if(Route::has('cv.download'))
                    <a href="{{ route('cv.download') }}" class="clay-btn bg-accent/90 hover:bg-accentlight text-slate-950 font-sans font-bold uppercase tracking-wider text-[10px] sm:text-xs px-3 py-1.5 sm:px-5 sm:py-2.5 inline-flex items-center gap-1.5 cursor-pointer shadow-md">
                        <span>Download CV</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </a>
                    @endif

                    <!-- Light / Dark Mode Toggle Button -->
                    <button @click="$store.theme.toggle()"
                            class="clay-btn p-2 sm:p-2.5 rounded-full bg-white/10 dark:bg-[#0a0d18]/90 border border-amber-500/40 hover:border-amber-400 text-amber-600 dark:text-amber-300 transition-all duration-300 cursor-pointer shadow-md flex items-center justify-center group"
                            :title="$store.theme.mode === 'dark' ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'">
                        <!-- Moon Icon (Shown in Light Mode) -->
                        <svg x-show="$store.theme.mode === 'light'" class="w-4 h-4 text-amber-600 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <!-- Sun Icon (Shown in Dark Mode - Default) -->
                        <svg x-show="$store.theme.mode === 'dark'" class="w-4 h-4 text-amber-400 group-hover:rotate-45 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-gray-300 hover:text-amber-400 focus:outline-none cursor-pointer p-2 rounded-lg bg-white/5 hover:bg-white/10 transition">
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg x-show="mobileMenuOpen" style="display: none;" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Menu Teleport to Body -->
            <template x-teleport="body">
                <div>
                    <!-- Mobile Menu Backdrop -->
                    <div x-show="mobileMenuOpen"
                         x-transition:enter="transition-opacity ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition-opacity ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click="mobileMenuOpen = false"
                         @touchmove.prevent
                         style="display: none;"
                         class="fixed inset-0 bg-black/70 backdrop-blur-sm z-[9990] md:hidden">
                    </div>

                    <!-- Mobile Menu Right Sidebar Drawer -->
                    <div x-show="mobileMenuOpen"
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="translate-x-full"
                         x-transition:enter-end="translate-x-0"
                         x-transition:leave="transition ease-in duration-200 transform"
                         x-transition:leave-start="translate-x-0"
                         x-transition:leave-end="translate-x-full"
                         style="display: none;"
                         class="fixed top-0 right-0 bottom-0 w-72 sm:w-80 bg-[#0a0d18]/98 backdrop-blur-2xl border-l border-amber-500/20 shadow-2xl z-[9991] flex flex-col justify-between p-6 overflow-y-auto md:hidden text-left">
                        
                        <div>
                            <!-- Sidebar Header -->
                            <div class="flex items-center justify-between pb-6 border-b border-amber-500/10 mb-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-amber-400/10 border border-amber-400/30 flex items-center justify-center text-amber-400 font-heading font-bold text-sm">
                                        R
                                    </div>
                                    <span class="font-heading font-bold text-white text-base tracking-wider">NAVIGASI</span>
                                </div>
                                <button @click="mobileMenuOpen = false" class="text-gray-400 hover:text-white p-2 rounded-lg bg-white/5 hover:bg-white/10 transition cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Navigation Links -->
                            <ul class="flex flex-col gap-3 text-xs uppercase tracking-[0.15em] text-gray-300 font-medium">
                                <li>
                                    <a @click="mobileMenuOpen = false" 
                                       href="{{ route('home') }}#beranda" 
                                       class="transition flex items-center pl-4 py-3 hover:text-amber-400 hover:bg-white/5 rounded-r-lg cursor-pointer">
                                        <svg class="w-4 h-4 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                        Beranda
                                    </a>
                                </li>
                                <li>
                                    <a @click="mobileMenuOpen = false" 
                                       href="{{ route('projects.index') }}" 
                                       class="transition flex items-center text-amber-400 bg-amber-400/10 border-l-2 border-amber-400 font-bold pl-4 py-3 rounded-r-lg cursor-pointer">
                                        <svg class="w-4 h-4 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        Proyek
                                    </a>
                                </li>
                                <li>
                                    <a @click="mobileMenuOpen = false" 
                                       href="{{ route('certificates.index') }}" 
                                       class="transition flex items-center pl-4 py-3 hover:text-amber-400 hover:bg-white/5 rounded-r-lg cursor-pointer">
                                        <svg class="w-4 h-4 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                                        Sertifikat
                                    </a>
                                </li>
                                <li>
                                    <a @click="mobileMenuOpen = false" 
                                       href="{{ route('home') }}#kontak" 
                                       class="transition flex items-center pl-4 py-3 hover:text-amber-400 hover:bg-white/5 rounded-r-lg cursor-pointer">
                                        <svg class="w-4 h-4 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        Kontak
                                    </a>
                                </li>
                                @auth
                                <li>
                                    <a @click="mobileMenuOpen = false" 
                                       href="{{ url('/admin') }}" 
                                       class="transition flex items-center pl-4 py-3 text-amber-400 font-bold hover:bg-amber-400/10 rounded-lg cursor-pointer">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        Admin Panel
                                    </a>
                                </li>
                                @endauth
                            </ul>
                        </div>

                        <!-- Sidebar Footer -->
                        <div class="pt-6 border-t border-amber-500/10 mt-auto">
                            @if(Route::has('cv.download'))
                            <a @click="mobileMenuOpen = false" href="{{ route('cv.download') }}" class="w-full clay-btn bg-accent hover:bg-accentlight text-slate-950 font-sans font-bold uppercase tracking-wider text-xs px-5 py-3 flex items-center justify-center gap-2 cursor-pointer shadow-lg">
                                <span>Download CV</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </template>
        </nav>
    </div>
    </div>

    {{-- ══════════════════════════════════════════
         MAIN CONTENT — FILTER BAR + 3-COLUMN GRID
    ══════════════════════════════════════════ --}}
    <main id="projects-grid" class="flex-grow relative z-10 pt-28 sm:pt-32 px-6 md:px-12 pb-24">
        <div class="max-w-7xl mx-auto">

            <!-- Back to Homepage Button -->
            <div class="mb-6">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-full bg-[#0a0d18]/90 border border-amber-400/30 hover:border-amber-400 text-amber-300 hover:text-amber-400 text-xs font-mono font-bold transition-all duration-300 shadow-[0_0_15px_rgba(212,175,55,0.1)] hover:shadow-[0_0_20px_rgba(212,175,55,0.3)] hover:-translate-x-1 backdrop-blur-md cursor-pointer group">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>

            <!-- Subheader & Filter Control Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="font-heading text-2xl sm:text-4xl text-white font-bold tracking-tight mb-1">
                        Semua Proyek
                    </h1>
                    <p class="text-slate-400 font-sans text-xs sm:text-sm">Jelajahi dan filter karya digital yang telah dikerjakan.</p>
                </div>

                <!-- Right Controls: Search Input & Sort Order Dropdown -->
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <!-- Live Search Input -->
                    <div class="relative flex-1 md:w-64">
                        <input type="search" x-model="search" aria-label="Cari proyek" placeholder="Cari nama atau teknologi..."
                               class="w-full bg-[#080a15]/90 border border-amber-400/25 focus:border-amber-400 rounded-xl px-4 py-2.5 pl-10 pr-8 text-xs font-sans text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-amber-400/50 transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)]">
                        <svg class="w-4 h-4 text-amber-400/60 absolute left-3.5 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <button x-show="search.length > 0" @click="search = ''" aria-label="Hapus pencarian" class="absolute right-3 top-3 text-slate-500 hover:text-white transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Sort Order Select Dropdown -->
                    <div class="relative shrink-0 flex items-center gap-1.5">
                        <label for="project-sort" class="text-xs font-mono text-slate-400 hidden sm:inline">Urutkan:</label>
                        <select id="project-sort" x-model="sortOrder" aria-label="Urutkan proyek" class="bg-[#080a15]/90 border border-amber-400/30 text-amber-300 text-xs font-mono rounded-xl px-3 py-2.5 focus:outline-none focus:border-amber-400 cursor-pointer">
                            <option value="terbaru">Terbaru</option>
                            <option value="terlama">Terlama</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Category Filter Pills (Horizontal Scroll on Mobile, Flex Wrap on Desktop) -->
            <div class="flex items-center gap-2 overflow-x-auto snap-x hide-scrollbar pb-3 mb-8 -mx-6 px-6 md:mx-0 md:px-0 flex-nowrap md:flex-wrap">
                <!-- Button SEMUA -->
                <button @click="activeCategory = 'SEMUA'"
                    :aria-pressed="activeCategory === 'SEMUA'"
                        :class="activeCategory === 'SEMUA'
                        ? 'bg-amber-400 text-slate-950 font-semibold border-amber-400'
                        : 'bg-transparent text-slate-300 hover:text-amber-300 border-slate-700 hover:border-amber-400/50'"
                    class="px-4 py-2 rounded-full border text-xs font-mono uppercase tracking-wider transition-colors duration-200 cursor-pointer shrink-0 snap-start flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full" :class="activeCategory === 'SEMUA' ? 'bg-slate-950' : 'bg-amber-400'"></span>
                    <span>Semua Kategori</span>
                </button>

                <!-- Dynamic Category Buttons -->
                <template x-for="cat in categories" :key="cat">
                    <button @click="activeCategory = cat"
                            :aria-pressed="activeCategory === cat"
                            :class="activeCategory === cat
                                ? 'bg-amber-400 text-slate-950 font-semibold border-amber-400'
                                : 'bg-transparent text-slate-300 hover:text-amber-300 border-slate-700 hover:border-amber-400/50'"
                            class="px-4 py-2 rounded-full border text-xs font-mono uppercase tracking-wider transition-colors duration-200 cursor-pointer shrink-0 snap-start flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full" :class="activeCategory === cat ? 'bg-slate-950' : 'bg-amber-400'"></span>
                        <span x-text="cat"></span>
                    </button>
                </template>
            </div>

            {{-- Projects Grid — 3 Columns Grid --}}
            <p class="mb-4 text-sm text-slate-400" role="status" aria-live="polite">
                <span x-text="filteredProjectCount"></span> proyek ditemukan
            </p>
            <div x-ref="projectGrid" x-effect="refreshProjects()" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-6">
                @forelse($projects as $i => $project)
                <div data-project-card
                     data-title="{{ $project->title }}"
                     data-category="{{ $project->category }}"
                     data-description="{{ $project->description }}"
                     data-tech-stack="{{ implode(', ', is_array($project->tech_stack) ? $project->tech_stack : []) }}"
                     data-created-at="{{ $project->created_at ? \Illuminate\Support\Carbon::parse($project->created_at)->timestamp : 0 }}"
                     data-project-order="{{ $i }}"
                     class="transition-opacity duration-200"
                     style="animation-delay: {{ ($i % 9) * 50 }}ms">
                    
                    <a href="{{ route('project.show', $project->slug) }}"
                       class="group relative p-5 sm:p-6 rounded-xl bg-[#0a0d18]/80 border border-amber-400/25 hover:border-amber-400/60 transition-colors duration-200 cursor-pointer flex flex-col justify-between h-full overflow-hidden block z-10">
                        <div class="relative z-10">
                            <!-- Top Image Box Container -->
                            <div class="w-full h-48 rounded-lg overflow-hidden border border-amber-400/20 relative mb-4 bg-[#070912]">
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
                                    <span class="project-category-badge inline-flex items-center gap-1.5 px-3 py-1 rounded-full border border-amber-400/40 bg-[#080a15]/90 text-amber-300 font-mono text-[10px] font-bold uppercase tracking-widest">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                        {{ $project->category }}
                                    </span>
                                </div>
                            </div>

                            <!-- Tech Stack Badges -->
                            <div class="flex flex-wrap gap-1.5 mb-3">
                                @php
                                    $stacks = is_array($project->tech_stack) ? $project->tech_stack : [];
                                    $displayStacks = array_slice($stacks, 0, 3);
                                @endphp
                                @foreach($displayStacks as $stack)
                                    <span class="project-tech-badge px-2.5 py-0.5 rounded-full border border-amber-400/20 bg-slate-950/40 text-[10px] font-mono text-slate-300 inline-flex items-center gap-1">
                                        <span class="w-1 h-1 rounded-full bg-amber-400/80"></span>
                                        {{ $stack }}
                                    </span>
                                @endforeach
                                @if(count($stacks) > 3)
                                    <span class="px-2 py-0.5 rounded-full border border-slate-800 text-[10px] font-mono text-slate-400">
                                        +{{ count($stacks) - 3 }}
                                    </span>
                                @endif
                            </div>

                            <!-- Title -->
                            <h3 class="font-heading font-bold text-xl text-white group-hover:text-amber-300 transition-colors mb-4 line-clamp-2">
                                {{ $project->title }}
                            </h3>
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
                @if($projects->isNotEmpty())
                <div x-cloak x-show="filteredProjectCount === 0" class="col-span-full py-16 text-center" role="status">
                    <p class="font-heading text-lg font-semibold text-slate-300">Tidak ada proyek yang cocok.</p>
                    <p class="mt-2 text-sm text-slate-500">Coba ubah kata kunci atau pilih kategori lain.</p>
                    <button @click="search = ''; activeCategory = 'SEMUA'" class="mt-4 text-sm font-semibold text-amber-300 underline underline-offset-4">
                        Reset filter
                    </button>
                </div>
                @endif
            </div>

            <div x-show="filteredProjectCount > visibleCount" class="mt-10 text-center">
                <button @click="visibleCount += 6"
                        class="group clay-btn bg-accent hover:bg-accentlight text-slate-950 font-sans font-bold uppercase tracking-wider text-xs px-6 py-3 cursor-pointer inline-flex items-center gap-2.5">
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
