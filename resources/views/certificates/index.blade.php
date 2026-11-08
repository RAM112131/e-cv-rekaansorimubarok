<!DOCTYPE html>
<html lang="id" class="scroll-smooth bg-slate-950 dark">
<head>
    <x-theme-preference />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('components.navigation-prefetch')
    <title>Semua Sertifikat — {{ $user->name ?? 'Portfolio' }}</title>
    <meta name="description" content="Kumpulan sertifikat, penghargaan, dan validasi keahlian teknis. Jelajahi seluruh pencapaian {{ $user->name ?? '' }}.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('certificates.index') }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="Semua Sertifikat — {{ $user->name ?? 'Portfolio' }}">
    <meta property="og:description" content="Kumpulan sertifikat dan penghargaan resmi.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('certificates.index') }}">

    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500;700&family=Playfair+Display:ital,wght@0,400;0,600;1,400;1,600&display=swap" rel="stylesheet">

    <style>
        :root {
            --font-heading: 'Syne', sans-serif;
            --font-sans:    'Inter', sans-serif;
            --font-mono:    'JetBrains Mono', monospace;
            --font-script:  'Playfair Display', serif;
            --color-accent:      #d4af37;
            --color-accentlight: #fde68a;
        }
        .font-heading { font-family: var(--font-heading); }
        .font-sans    { font-family: var(--font-sans); }
        .font-mono    { font-family: var(--font-mono); }
        .font-serif   { font-family: var(--font-script); }
        .text-accent       { color: var(--color-accent); }
        .text-accentlight  { color: var(--color-accentlight); }
        .bg-accent         { background-color: var(--color-accent); }
        .border-accent     { border-color: var(--color-accent); }

        [x-cloak] { display: none !important; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--color-accent); }

        /* Grid fade-in */
    </style>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-950 text-white font-sans antialiased overflow-x-hidden selection:bg-amber-400/30 selection:text-white min-h-screen flex flex-col">

    <!-- Swarm Cursor Root -->

    {{-- ══════════════════════════════════════════
         NAVBAR — identical to welcome.blade.php
    ══════════════════════════════════════════ --}}
    <!-- Fixed Navigation Menu -->
    <div class="fixed top-0 left-0 right-0 z-50 flex justify-center p-4 transition-all duration-300">
        <nav x-data="{ 
                 mobileMenuOpen: false, 
                 scrolled: false,
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
                    <li><a href="{{ route('projects.index') }}" class="nav-link-futuristic hover:text-accentlight transition duration-200 cursor-pointer">Proyek</a></li>
                    <li><a href="{{ route('certificates.index') }}" class="nav-link-futuristic text-accentlight font-bold active cursor-pointer">Sertifikat</a></li>
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
                                       class="transition flex items-center pl-4 py-3 hover:text-amber-400 hover:bg-white/5 rounded-r-lg cursor-pointer">
                                        <svg class="w-4 h-4 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        Proyek
                                    </a>
                                </li>
                                <li>
                                    <a @click="mobileMenuOpen = false" 
                                       href="{{ route('certificates.index') }}" 
                                       class="transition flex items-center text-amber-400 bg-amber-400/10 border-l-2 border-amber-400 font-bold pl-4 py-3 rounded-r-lg cursor-pointer">
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
        </nav>
    </div>

    @php
        $dbCategories = isset($categories) && $categories->count() > 0
            ? $categories->pluck('name')->toArray()
            : \App\Models\CertificateCategory::orderBy('order', 'asc')->pluck('name')->toArray();

        $issuers = $certificates->pluck('issuer')->filter()->unique()->values();
        $certsArray = $certificates->map(function($cert) {
            $certUrl = $cert->credential_url ?? $cert->url ?? null;
            $certImg = $cert->image ? Storage::disk('public')->url($cert->image) : null;
            return [
                'id' => $cert->id,
                'title' => $cert->title,
                'category' => $cert->certificateCategory?->name ?? $cert->category ?? null,
                'issuer' => $cert->issuer ?? null,
                'date' => $cert->issue_date ? \Carbon\Carbon::parse($cert->issue_date)->format('d M Y') : null,
                'issue_date' => $cert->issue_date ? \Carbon\Carbon::parse($cert->issue_date)->format('Y-m-d') : '',
                'expiration_date' => $cert->expiration_date ? \Carbon\Carbon::parse($cert->expiration_date)->format('d M Y') : null,
                'credential_id' => $cert->credential_id ?? null,
                'description' => $cert->description ?? null,
                'image' => $certImg,
                'url' => $certUrl,
            ];
        })->values();
    @endphp

    {{-- ══════════════════════════════════════════
         MAIN CONTENT — 3-Column Grid with Dynamic Category & Issuer Filter Controls
    ══════════════════════════════════════════ --}}
    <main class="flex-grow relative z-10 pt-28 sm:pt-32 px-6 md:px-12 pb-16"
          x-data="{
            allCerts: {{ json_encode($certsArray) }},
            categories: {{ json_encode($dbCategories) }},
            issuers: {{ json_encode($issuers) }},
            selectedCategory: 'all',
            selectedIssuer: 'all',
            search: '',
            sort: 'newest',
            activeModalCert: null,
            zoomScale: 1,
            zoomX: 0,
            zoomY: 0,
            isDragging: false,
            startX: 0,
            startY: 0,
            zoomIn() {
                this.zoomScale = Math.min(4, Math.round((this.zoomScale + 0.3) * 100) / 100);
            },
            zoomOut() {
                this.zoomScale = Math.max(0.6, Math.round((this.zoomScale - 0.3) * 100) / 100);
                if (this.zoomScale <= 1) {
                    this.zoomX = 0;
                    this.zoomY = 0;
                }
            },
            resetZoom() {
                this.zoomScale = 1;
                this.zoomX = 0;
                this.zoomY = 0;
                this.isDragging = false;
            },
            openCertModal(cert) {
                this.activeModalCert = cert;
                this.resetZoom();
            },
            closeCertModal() {
                this.activeModalCert = null;
                this.resetZoom();
            },
            handleWheel(e) {
                if (e.deltaY < 0) {
                    this.zoomIn();
                } else {
                    this.zoomOut();
                }
            },
            startDrag(e) {
                if (this.zoomScale <= 1) return;
                this.isDragging = true;
                this.startX = e.clientX - this.zoomX;
                this.startY = e.clientY - this.zoomY;
            },
            onDrag(e) {
                if (!this.isDragging) return;
                this.zoomX = e.clientX - this.startX;
                this.zoomY = e.clientY - this.startY;
            },
            stopDrag() {
                this.isDragging = false;
            },
            filterCert(c) {
                if (this.selectedCategory !== 'all') {
                    let certCat = (c.category || '').toLowerCase();
                    let selCat = this.selectedCategory.toLowerCase();
                    if (certCat) {
                        if (certCat !== selCat) return false;
                    } else {
                        // Fallback matching against title/issuer if category relation is not assigned yet
                        let text = (c.title + ' ' + (c.issuer || '')).toLowerCase();
                        let key = selCat.split(' ')[0];
                        if (!text.includes(key)) return false;
                    }
                }
                if (this.selectedIssuer !== 'all' && c.issuer !== this.selectedIssuer) {
                    return false;
                }
                if (this.search.trim() !== '') {
                    let q = this.search.toLowerCase();
                    let matchTitle = (c.title || '').toLowerCase().includes(q);
                    let matchCategory = (c.category || '').toLowerCase().includes(q);
                    let matchIssuer = (c.issuer || '').toLowerCase().includes(q);
                    let matchId = (c.credential_id || '').toLowerCase().includes(q);
                    if (!matchTitle && !matchCategory && !matchIssuer && !matchId) return false;
                }
                return true;
            },
            get filteredList() {
                let res = this.allCerts.filter(c => this.filterCert(c));
                if (this.sort === 'oldest') {
                    return res.sort((a, b) => new Date(a.issue_date || '1970-01-01') - new Date(b.issue_date || '1970-01-01'));
                }
                return res.sort((a, b) => new Date(b.issue_date || '1970-01-01') - new Date(a.issue_date || '1970-01-01'));
            }
          }">

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
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="font-heading text-2xl sm:text-4xl text-white font-bold tracking-tight mb-1">
                        Semua Sertifikat
                    </h1>
                    <p class="text-slate-400 font-sans text-xs sm:text-sm">Jelajahi dan filter daftar sertifikasi resmi yang terdaftar.</p>
                </div>

                <!-- Right Controls Container -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
                    <!-- Live Search Input -->
                    <div class="relative w-full sm:w-56">
                        <input type="text" x-model="search" placeholder="Cari sertifikat..."
                               class="w-full bg-[#080a15]/90 border border-amber-400/25 focus:border-amber-400 rounded-xl px-4 py-2.5 pl-10 pr-8 text-xs font-sans text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-amber-400/50 transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)]">
                        <svg class="w-4 h-4 text-amber-400/60 absolute left-3.5 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <button x-show="search.length > 0" @click="search = ''" class="absolute right-3 top-3 text-slate-500 hover:text-white transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Dropdowns Row (2 Columns on Mobile, Flex on Desktop) -->
                    <div class="grid grid-cols-2 sm:flex items-center gap-2 sm:gap-3 w-full sm:w-auto">
                        <!-- Filter Penerbit Dropdown -->
                        <template x-if="issuers.length > 0">
                            <div class="relative w-full sm:w-auto flex flex-col sm:flex-row sm:items-center gap-1">
                                <label class="text-[10px] font-mono uppercase tracking-wider text-slate-400 sm:hidden">Penerbit</label>
                                <select x-model="selectedIssuer" class="w-full sm:w-auto bg-[#080a15]/90 border border-amber-400/30 text-amber-300 text-xs font-mono rounded-xl px-3 py-2.5 focus:outline-none focus:border-amber-400 cursor-pointer shadow-[inset_0_1px_3px_rgba(255,255,255,0.05),0_4px_10px_rgba(0,0,0,0.4)]">
                                    <option value="all">Semua Penerbit</option>
                                    <template x-for="issuer in issuers" :key="issuer">
                                        <option :value="issuer" x-text="issuer"></option>
                                    </template>
                                </select>
                            </div>
                        </template>

                        <!-- Sort Order Dropdown -->
                        <div class="relative w-full sm:w-auto flex flex-col sm:flex-row sm:items-center gap-1">
                            <label class="text-[10px] font-mono uppercase tracking-wider text-slate-400 sm:hidden">Urutkan</label>
                            <select x-model="sort" class="w-full sm:w-auto bg-[#080a15]/90 border border-amber-400/30 text-amber-300 text-xs font-mono rounded-xl px-3 py-2.5 focus:outline-none focus:border-amber-400 cursor-pointer shadow-[inset_0_1px_3px_rgba(255,255,255,0.05),0_4px_10px_rgba(0,0,0,0.4)]">
                                <option value="newest">Terbaru</option>
                                <option value="oldest">Terlama</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Filter Pills (Horizontal Scroll on Mobile, Flex Wrap on Desktop) -->
            <div class="flex items-center gap-2 overflow-x-auto snap-x hide-scrollbar pb-3 mb-8 -mx-4 px-4 sm:mx-0 sm:px-0 flex-nowrap md:flex-wrap">
                <!-- Button SEMUA -->
                <button @click="selectedCategory = 'all'"
                        :class="selectedCategory === 'all' 
                            ? 'bg-amber-400 text-slate-950 font-bold border-amber-400 shadow-[0_0_15px_rgba(212,175,55,0.35)] scale-[1.02]' 
                            : 'bg-[#0a0d18]/80 text-slate-300 hover:text-amber-300 border-amber-400/20 hover:border-amber-400/50 hover:bg-amber-400/5'"
                        class="clay-card px-3.5 py-2 rounded-full border text-xs font-mono uppercase tracking-wider transition-all duration-300 cursor-pointer shrink-0 snap-start flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full" :class="selectedCategory === 'all' ? 'bg-slate-950' : 'bg-amber-400 animate-pulse'"></span>
                    <span>Semua Kategori</span>
                </button>

                <!-- Dynamic Category Buttons -->
                <template x-for="cat in categories" :key="cat">
                    <button @click="selectedCategory = cat"
                            :class="selectedCategory === cat 
                                ? 'bg-amber-400 text-slate-950 font-bold border-amber-400 shadow-[0_0_15px_rgba(212,175,55,0.35)] scale-[1.02]' 
                                : 'bg-[#0a0d18]/80 text-slate-300 hover:text-amber-300 border-amber-400/20 hover:border-amber-400/50 hover:bg-amber-400/5'"
                            class="clay-card px-3.5 py-2 rounded-full border text-xs font-mono uppercase tracking-wider transition-all duration-300 cursor-pointer shrink-0 snap-start flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full" :class="selectedCategory === cat ? 'bg-slate-950' : 'bg-amber-400'"></span>
                        <span x-text="cat"></span>
                    </button>
                </template>
            </div>

            <!-- 3-Column Grid (Hero Claymorphism Style) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">

                <template x-for="(cert, index) in filteredList" :key="cert.id || index">
                    <div class="group relative clay-card p-5 sm:p-6 rounded-2xl bg-[#0a0d18]/70 border border-amber-400/30 hover:border-amber-400/70 transition-all duration-500 hover:-translate-y-2.5 hover:shadow-[0_0_30px_rgba(212,175,55,0.25)] cursor-pointer flex flex-col justify-between overflow-hidden relative z-10 backdrop-blur-md"
                         @click="openCertModal(cert)">
                        
                        <template x-if="cert.image">
                            <!-- Visible Certificate Cover Image Card Background Layer -->
                            <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none rounded-2xl">
                                <img :src="cert.image" alt="" 
                                     class="w-full h-full object-cover scale-105 group-hover:scale-110 opacity-40 group-hover:opacity-60 transition-all duration-700 select-none pointer-events-none"
                                     oncontextmenu="return false;"
                                     ondragstart="return false;"
                                     draggable="false">
                                <div class="absolute inset-0 bg-gradient-to-r from-[#060815]/95 via-[#060815]/80 to-[#060815]/30"></div>
                                <div class="absolute inset-0 bg-gradient-to-t from-[#060815]/95 via-transparent to-[#060815]/30"></div>
                            </div>
                        </template>

                        <!-- Ambient Glowing Orbs Background (Pure Gold Theme matching Hero) -->
                        <div class="absolute -top-14 -right-14 w-48 h-48 bg-amber-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-amber-500/30 transition-all duration-700 z-0"></div>
                        <div class="absolute -bottom-14 -left-14 w-48 h-48 bg-amber-500/15 rounded-full blur-3xl pointer-events-none group-hover:bg-amber-500/25 transition-all duration-700 z-0"></div>
                        
                        <!-- Futuristic Grid Pattern Watermark Overlay -->
                        <div class="absolute inset-0 bg-[radial-gradient(#d4af37_1px,transparent_1px)] [background-size:20px_20px] opacity-[0.05] group-hover:opacity-[0.1] transition-opacity duration-500 pointer-events-none z-0"></div>

                        <div class="relative z-10 flex flex-col justify-between h-full">
                            <div>
                                <!-- Certificate Image Viewport Frame with Clay Card styling -->
                                <div class="clay-card relative w-full aspect-[16/10] rounded-2xl overflow-hidden bg-slate-950 border border-amber-400/30 mb-4 shadow-[0_10px_30px_rgba(0,0,0,0.8),inset_0_1px_3px_rgba(255,255,255,0.1)] group-hover:border-amber-400/60 transition-all select-none"
                                     @contextmenu.prevent
                                     @dragstart.prevent>
                                    
                                    <template x-if="cert.image">
                                        <div class="w-full h-full relative flex items-center justify-center overflow-hidden">
                                            <img :src="cert.image" :alt="cert.title" 
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 pointer-events-none select-none" 
                                                 oncontextmenu="return false;" 
                                                 ondragstart="return false;" 
                                                 loading="lazy">
                                            
                                            <!-- Protection Glass Overlay -->
                                            <div class="absolute inset-0 bg-transparent z-10" @contextmenu.prevent @dragstart.prevent></div>
                                            
                                            <!-- Security Watermark Layer -->
                                            <div class="absolute inset-0 z-20 pointer-events-none opacity-15 flex items-center justify-center rotate-[-25deg] select-none overflow-hidden">
                                                <span class="font-mono text-[9px] font-bold tracking-[0.3em] uppercase text-amber-300 whitespace-nowrap drop-shadow">VERIFIED PORTFOLIO • PREVIEW ONLY</span>
                                            </div>

                                            <!-- Hover Sheen Overlay -->
                                            <div class="absolute inset-0 bg-gradient-to-tr from-amber-400/0 via-amber-400/5 to-amber-400/15 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none z-30"></div>
                                        </div>
                                    </template>
                                    <template x-if="!cert.image">
                                        <!-- Dynamic 3D Holographic Certificate Vector Illustration -->
                                        <div class="w-full h-full bg-gradient-to-br from-[#12162a] via-[#090b16] to-[#04050a] flex flex-col items-center justify-center p-4 relative overflow-hidden">
                                            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(212,175,55,0.15)_0,transparent_70%)] pointer-events-none"></div>
                                            <div class="w-24 h-16 rounded-lg bg-[#181c35] border border-amber-400/30 p-2 shadow-lg flex flex-col justify-between transform -rotate-3 group-hover:rotate-0 transition-transform duration-500 relative z-10">
                                                <div class="flex items-center justify-between">
                                                    <div class="w-4 h-4 rounded-full bg-amber-400/20 border border-amber-400/40 flex items-center justify-center">
                                                        <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                                                    </div>
                                                    <div class="w-8 h-1 bg-amber-400/30 rounded-full"></div>
                                                </div>
                                                <div class="space-y-1">
                                                    <div class="w-full h-1 bg-slate-700/60 rounded-full"></div>
                                                    <div class="w-3/4 h-1 bg-slate-700/60 rounded-full"></div>
                                                </div>
                                                <div class="flex justify-end">
                                                    <div class="w-3 h-3 rounded-full bg-amber-500/30 border border-amber-400/40"></div>
                                                </div>
                                            </div>
                                            <span class="font-mono text-[9px] uppercase tracking-widest text-amber-300 font-bold mt-2 relative z-10">OFFICIAL CERTIFICATE</span>
                                        </div>
                                    </template>
                                </div>

                                <!-- Title & Category Pill -->
                                <div class="space-y-2">
                                    <template x-if="cert.category">
                                        <span class="clay-card inline-flex items-center gap-1.5 px-3 py-1 rounded-full border border-amber-400/35 text-amber-300 font-mono text-[10px] font-bold uppercase tracking-widest shadow-[0_0_12px_rgba(212,175,55,0.15)] mb-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                            <span x-text="cert.category"></span>
                                        </span>
                                    </template>

                                    <div class="flex items-start justify-between gap-3">
                                        <h3 class="font-heading font-bold text-lg text-white group-hover:text-amber-300 transition-colors leading-snug line-clamp-2"
                                            x-text="cert.title"></h3>

                                        <a x-show="cert.url" :href="cert.url" target="_blank" @click.stop
                                           class="clay-circle w-8 h-8 flex items-center justify-center text-amber-300 hover:text-slate-950 hover:bg-amber-400 border border-amber-400/30 transition-all flex-shrink-0 mt-0.5"
                                           title="Buka Kredensial">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Sub-metadata Bottom Bar: Issuer • Date -->
                            <template x-if="cert.issuer || cert.date || cert.credential_id">
                                <div class="flex items-center justify-between text-xs font-mono pt-3 mt-3 border-t border-slate-800/80">
                                    <div class="flex items-center gap-2 truncate pr-2">
                                        <template x-if="cert.issuer">
                                            <span class="text-amber-400 font-semibold truncate max-w-[140px]" x-text="cert.issuer"></span>
                                        </template>
                                        <template x-if="cert.issuer && cert.date">
                                            <span class="text-slate-600 font-bold">•</span>
                                        </template>
                                        <template x-if="cert.date">
                                            <span class="text-slate-400" x-text="cert.date"></span>
                                        </template>
                                    </div>
                                    <template x-if="cert.credential_id">
                                        <span class="text-[10px] font-mono text-slate-400/80 truncate max-w-[90px]" x-text="'ID:' + cert.credential_id"></span>
                                    </template>
                                </div>
                            </template>
                        </div>

                    </div>
                </template>

            </div>

            <!-- Empty Filter Result State -->
            <template x-if="filteredList.length === 0">
                <div class="py-16 text-center bg-[#0e101e]/60 rounded-3xl border border-slate-800 mb-12">
                    <p class="text-slate-500 font-mono text-sm">Tidak ada sertifikat yang cocok dengan pencarian Anda.</p>
                </div>
            </template>

            

            <!-- Spotlight Lightbox Modal (Teleported to Body) -->
             <template x-teleport="body">
                <div x-show="activeModalCert"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 backdrop-blur-0"
                     x-transition:enter-end="opacity-100 backdrop-blur-xl"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 backdrop-blur-xl"
                     x-transition:leave-end="opacity-0 backdrop-blur-0"
                     class="fixed inset-0 z-[9999] bg-black/95 backdrop-blur-xl flex items-center justify-center p-4 sm:p-8 pt-20 pb-12 overflow-y-auto"
                     @click.self="closeCertModal()"
                     @keydown.escape.window="closeCertModal()"
                     style="display: none;">
                    
                    <div class="relative max-w-5xl w-full bg-gradient-to-b from-[#111528] via-[#0d1020] to-[#070914] rounded-[32px] border border-amber-400/30 p-6 sm:p-8 shadow-[0_25px_60px_rgba(0,0,0,0.9),0_0_50px_rgba(212,175,55,0.15)] overflow-hidden my-auto backdrop-blur-2xl"
                         x-show="activeModalCert"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                        <!-- Close Button -->
                        <button @click="closeCertModal()"
                                class="absolute top-4 right-4 sm:top-5 sm:right-5 z-30 w-10 h-10 rounded-full bg-[#070914] text-slate-400 hover:text-amber-300 border border-amber-400/30 hover:border-amber-400 flex items-center justify-center transition-all shadow-lg cursor-pointer">
                            &times;
                        </button>

                        <template x-if="activeModalCert">
                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-center">
                                
                                <!-- LEFT COLUMN (Cols 1-7): Certificate Image / 3D Graphic Preview with Interactive Zoom -->
                                <div class="lg:col-span-7 w-full">
                                    <div class="relative w-full aspect-[16/11] rounded-2xl overflow-hidden bg-slate-950 border border-amber-400/25 shadow-[inset_0_2px_10px_rgba(0,0,0,0.8),0_10px_30px_rgba(0,0,0,0.7)] flex items-center justify-center select-none"
                                         @wheel.prevent="handleWheel($event)"
                                         @mousedown="startDrag($event)"
                                         @mousemove.window="onDrag($event)"
                                         @mouseup.window="stopDrag()"
                                         @mouseleave="stopDrag()"
                                         :class="zoomScale > 1 ? (isDragging ? 'cursor-grabbing' : 'cursor-grab') : 'cursor-zoom-in'"
                                         @dblclick="zoomScale === 1 ? (zoomScale = 2) : resetZoom()">
                                        
                                        <!-- Floating Zoom Controls Toolbar -->
                                        <div class="absolute top-3 right-3 z-30 flex items-center gap-1.5 p-1.5 rounded-full bg-[#070914]/85 backdrop-blur-md border border-amber-400/30 shadow-xl"
                                             @click.stop>
                                            <!-- Zoom Out Button -->
                                            <button @click="zoomOut()" 
                                                    :disabled="zoomScale <= 0.6"
                                                    class="w-7 h-7 rounded-full bg-slate-900 hover:bg-amber-400 hover:text-slate-950 text-amber-300 flex items-center justify-center text-sm font-bold transition-all disabled:opacity-30 cursor-pointer"
                                                    title="Zoom Out (-)">
                                                −
                                            </button>

                                            <!-- Reset / Scale Badge -->
                                            <button @click="resetZoom()" 
                                                    class="px-2.5 py-0.5 rounded-full bg-amber-400/10 hover:bg-amber-400/20 text-amber-300 font-mono text-[10px] font-bold tracking-wider transition-all cursor-pointer"
                                                    title="Klik untuk Reset Zoom (100%)">
                                                <span x-text="Math.round(zoomScale * 100) + '%'">100%</span>
                                            </button>

                                            <!-- Zoom In Button -->
                                            <button @click="zoomIn()" 
                                                    :disabled="zoomScale >= 4"
                                                    class="w-7 h-7 rounded-full bg-slate-900 hover:bg-amber-400 hover:text-slate-950 text-amber-300 flex items-center justify-center text-sm font-bold transition-all disabled:opacity-30 cursor-pointer"
                                                    title="Zoom In (+)">
                                                +
                                            </button>
                                        </div>

                                        <!-- Zoomable Inner Wrapper -->
                                        <div class="w-full h-full relative flex items-center justify-center p-2 origin-center"
                                             :style="'transform: scale(' + zoomScale + ') translate(' + (zoomX / zoomScale) + 'px, ' + (zoomY / zoomScale) + 'px); ' + (isDragging ? 'transition: none;' : 'transition: transform 0.15s ease-out;')">
                                            
                                            <template x-if="activeModalCert.image">
                                                <img :src="activeModalCert.image" :alt="activeModalCert.title" 
                                                     class="max-w-full max-h-full object-contain rounded-lg pointer-events-none select-none">
                                            </template>

                                            <template x-if="!activeModalCert.image">
                                                <!-- Holographic 3D Graphic Placeholder if no image -->
                                                <div class="w-full h-full bg-gradient-to-br from-[#12162a] via-[#090b16] to-[#04050a] flex flex-col items-center justify-center p-6 relative overflow-hidden pointer-events-none select-none">
                                                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(212,175,55,0.15)_0,transparent_70%)] pointer-events-none"></div>
                                                    <div class="w-36 h-24 rounded-xl bg-[#181c35] border border-amber-400/40 p-3 shadow-2xl flex flex-col justify-between relative z-10">
                                                        <div class="flex items-center justify-between">
                                                            <div class="w-5 h-5 rounded-full bg-amber-400/20 border border-amber-400/50 flex items-center justify-center">
                                                                <div class="w-2.5 h-2.5 rounded-full bg-amber-400"></div>
                                                            </div>
                                                            <div class="w-12 h-1.5 bg-amber-400/30 rounded-full"></div>
                                                        </div>
                                                        <div class="space-y-1.5">
                                                            <div class="w-full h-1.5 bg-slate-700/60 rounded-full"></div>
                                                            <div class="w-3/4 h-1.5 bg-slate-700/60 rounded-full"></div>
                                                        </div>
                                                        <div class="flex justify-end">
                                                            <div class="w-4 h-4 rounded-full bg-amber-500/30 border border-amber-400/40"></div>
                                                        </div>
                                                    </div>
                                                    <span class="font-mono text-xs uppercase tracking-widest text-amber-300 font-bold mt-4 relative z-10">VERIFIED OFFICIAL CREDENTIAL</span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <!-- RIGHT COLUMN (Cols 8-12): Certificate Details -->
                                <div class="lg:col-span-5 flex flex-col justify-between space-y-4 w-full">
                                    <div class="space-y-4">
                                        <!-- Category Badge Tag -->
                                        <template x-if="activeModalCert.category">
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-400/10 border border-amber-400/30 text-amber-300 font-mono text-[10px] font-bold uppercase tracking-wider"
                                                      x-text="activeModalCert.category"></span>
                                            </div>
                                        </template>

                                        <!-- Certificate Title -->
                                        <h3 class="font-heading font-bold text-xl sm:text-2xl text-white leading-snug" x-text="activeModalCert.title"></h3>

                                        <!-- Issuer & Date Info Card (Strict Conditional Rendering) -->
                                        <template x-if="activeModalCert.issuer || activeModalCert.date || activeModalCert.expiration_date || activeModalCert.credential_id">
                                            <div class="bg-[#070914]/80 border border-slate-800 p-4 rounded-xl space-y-2.5 font-mono text-xs w-full">
                                                <template x-if="activeModalCert.issuer">
                                                    <div class="flex items-center justify-between text-slate-300">
                                                        <span class="text-slate-400">Penerbit:</span>
                                                        <span class="text-amber-400 font-bold" x-text="activeModalCert.issuer"></span>
                                                    </div>
                                                </template>
                                                <template x-if="activeModalCert.date">
                                                    <div class="flex items-center justify-between text-slate-300 pt-2 border-t border-slate-800/80">
                                                        <span class="text-slate-400">Tanggal Terbit:</span>
                                                        <span class="text-slate-200" x-text="activeModalCert.date"></span>
                                                    </div>
                                                </template>
                                                <template x-if="activeModalCert.expiration_date">
                                                    <div class="flex items-center justify-between text-slate-300 pt-2 border-t border-slate-800/80">
                                                        <span class="text-slate-400">Masa Berlaku:</span>
                                                        <span class="text-slate-200" x-text="activeModalCert.expiration_date"></span>
                                                    </div>
                                                </template>
                                                <template x-if="activeModalCert.credential_id">
                                                    <div class="flex items-center justify-between text-slate-300 pt-2 border-t border-slate-800/80">
                                                        <span class="text-slate-400">ID Kredensial:</span>
                                                        <span class="text-amber-300/90 font-mono text-[11px]" x-text="activeModalCert.credential_id"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>

                                        <!-- Description -->
                                        <template x-if="activeModalCert.description && activeModalCert.description.trim() !== ''">
                                            <div class="space-y-1 pt-1">
                                                <span class="text-[10px] font-mono uppercase tracking-widest text-slate-400 font-bold block">Deskripsi:</span>
                                                <p class="text-slate-300 font-sans text-xs leading-relaxed max-h-32 overflow-y-auto pr-1" x-text="activeModalCert.description"></p>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- External Link Button -->
                                    <template x-if="activeModalCert.url">
                                        <div class="pt-3 border-t border-slate-800">
                                            <a :href="activeModalCert.url" target="_blank"
                                               class="group clay-btn bg-accent hover:bg-accentlight text-slate-950 font-sans font-bold uppercase tracking-wider text-xs w-full py-3.5 px-5 flex items-center justify-center gap-2 cursor-pointer overflow-hidden transition-all duration-300 shadow-lg">
                                                <span>Buka Kredensial Resmi</span>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        </div>
                                    </template>
                                </div>

                            </div>
                        </template>
                    </div>
                </div>
            </template>

        </div>
    </main>

    {{-- ══════════════════════════════════════════
         FOOTER
    ══════════════════════════════════════════ --}}
    <footer class="relative z-10 border-t border-slate-900 py-10 px-6 md:px-12">
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
