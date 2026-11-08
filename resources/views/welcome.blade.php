<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth dark" style="scroll-padding-top: 80px;">
<head>
    <x-theme-preference />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('components.navigation-prefetch')
    {{-- ==================== SEO & Metadata Start ==================== --}}
  <title>
      {{ $seoTitle ?? $user->name ?? 'Rayhan Aditya' }} – Portfolio | Web Developer
  </title>

  <meta name="description"
        content="{{ $seoDescription ?? $user->bio ?? 'Saya seorang Web Developer yang fokus pada menciptakan pengalaman
  web yang fungsional, visual menarik, dan berdampak nyata.' }}">
  <meta name="keywords"
        content="{{ $seoKeywords ?? 'web developer, frontend, UI/UX, Laravel, React, Three.js, portfolio, Indonesia'
  }}">

  <!-- Canonical URL – gunakan URL absolut halaman ini -->
  <link rel="canonical"
        href="{{ url()->current() }}">

  <!-- Open Graph / Facebook -->
  <meta property="og:type"          content="website">
  <meta property="og:url"           content="{{ url()->current() }}">
  <meta property="og:title"         content="{{ $ogTitle ?? $user->name ?? 'Rayhan Aditya' }} – Portfolio">
  <meta property="og:description"   content="{{ $ogDescription ?? $user->bio ?? 'Portofolio saya yang menampilkan
  proyek, skill, dan pengalaman dalam pembangunan web.' }}">
  <meta property="og:image"         content="{{ $ogImage ?? asset('images/gambar-depan.webp') }}">
  <meta property="og:site_name"     content="{{ $user->name ?? 'Reka Ansori Mubarok' }} Portfolio">
  <meta property="og:locale"        content="id_ID">
  <meta property="og:locale:alternate" content="en_US">

  <!-- Twitter Card -->
  <meta name="twitter:card"         content="summary_large_image">
  <meta name="twitter:site"         content="@{{ $twitterHandle ?? 'your_twitter_handle' }}">
  <meta name="twitter:title"        content="{{ $twitterTitle ?? $user->name ?? 'Reka Ansori Mubarok' }} – Portfolio">
  <meta name="twitter:description"  content="{{ $twitterDescription ?? $user->bio ?? 'Saya seorang Web Developer yang
  fokus pada menciptakan pengalaman web yang fungsional, visual menarik, dan berdampak nyata.' }}">
  <meta name="twitter:image:src"    content="{{ $twitterImage ?? asset('images/gambar-depan.webp') }}">

  <!-- JSON‑LD Structured Data (Person) -->
  <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Person",
    "name": "{{ $jsonLdName ?? $user->name ?? 'Rayhan Aditya' }}",
    "url": "{{ url()->current() }}",
    "sameAs": [
      @foreach($user->socialLinks as $link)
        "@if(strtolower($link->platform) == 'github'){{ $link->url }}@elseif(strtolower($link->platform) ==
  'linkedin'){{ $link->url }}@elseif(strtolower($link->platform) == 'instagram'){{ $link->url
  }}@elseif(strtolower($link->platform) == 'twitter' || strtolower($link->platform) == 'x'){{ $link->url }}@endif",
      @endforeach
    ],
    "jobTitle": "{{ $jsonLdJobTitle ?? $user->headline ?? 'Web Developer' }}",
    "description": "{{ $jsonLdDescription ?? $user->bio ?? 'Saya seorang Web Developer yang fokus pada menciptakan
  pengalaman web yang fungsional, visual menarik, dan berdampak nyata.' }}",
    "image": "{{ $jsonLdImage ?? asset('images/og-portofolio.jpg') }}",
    "address": {
    "@@type": "PostalAddress",
      "addressLocality": "{{ $jsonLdCity ?? $user->location ?? 'Indonesia' }}",
      "addressCountry": "ID"
    }
  }
  </script>
  {{-- ==================== SEO & Metadata End ==================== --}}
    
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
        html.light .skill-modal {
            background-color: #FFFBEB !important;
            border-color: rgba(217, 119, 6, 0.4) !important;
            color: #292524 !important;
            box-shadow: 0 10px 32px rgba(120, 53, 15, 0.2) !important;
        }

        html.light .skill-modal h4 {
            color: #B45309 !important;
        }

        html.light .skill-modal p {
            color: #57534E !important;
        }

        html.light .skill-modal > div span {
            background-color: rgba(217, 119, 6, 0.14) !important;
            color: #92400E !important;
        }

        html.light .skill-modal > button {
            background-color: #FFFFFF !important;
            border-color: rgba(217, 119, 6, 0.35) !important;
            color: #57534E !important;
        }

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
        
        .huge-bg-text {
            position: absolute;
            top: 5%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 20vw;
            font-family: var(--font-heading);
            font-weight: 700;
            color: var(--color-accentlight);
            opacity: 0.05;
            white-space: nowrap;
            z-index: 0;
            line-height: 1;
            user-select: none;
        }
        
        .skill-tag {
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.35rem 1rem;
            font-size: 0.75rem;
            border-radius: 9999px;
            color: #cbd5e1;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            background: rgba(15, 23, 42, 0.5);
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
        
        /* Solar System Animations */
        .solar-orbit {
            transform-style: preserve-3d;
        }
        
        /* Clean orbit rotation — no translate in keyframe, centering done via negative margin on the element */
        @keyframes orbit-spin {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }
        
        /* Counter-rotate the planet so it faces the viewer and stays perfectly round */
        @keyframes planet-upright {
            from { transform: rotateZ(0deg)    rotateX(-82deg); }
            to   { transform: rotateZ(-360deg) rotateX(-82deg); }
        }
    </style>
</head>
<body class="antialiased">

    <!-- Global Floating Toast Notification (Success / Error) -->
    @if(session('contact_success') || session('contact_error') || $errors->any())
    <div x-data="{ showToast: true }"
         x-show="showToast"
         x-init="setTimeout(() => showToast = false, 7000)"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-y-[-20px] opacity-0 scale-95"
         x-transition:enter-end="translate-y-0 opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-y-0 opacity-100 scale-100"
         x-transition:leave-end="translate-y-[-20px] opacity-0 scale-95"
         class="fixed top-20 right-4 sm:right-8 z-[100] max-w-md w-full pointer-events-auto">
        
        @if(session('contact_success'))
        <div class="clay-card rounded-2xl p-4 bg-[#0a1610]/95 border border-emerald-500/40 shadow-[0_0_30px_rgba(16,185,129,0.3),inset_1px_1px_4px_rgba(255,255,255,0.1)] backdrop-blur-xl flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/20 border border-emerald-400/50 flex items-center justify-center text-emerald-400 flex-shrink-0 shadow-[0_0_15px_rgba(16,185,129,0.4)]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="font-heading font-bold text-sm text-white flex items-center justify-between">
                    <span>Pesan Berhasil Terkirim!</span>
                    <span class="text-[10px] font-mono text-emerald-400 uppercase tracking-widest bg-emerald-500/20 px-2 py-0.5 rounded-full border border-emerald-500/30">Terkirim</span>
                </h4>
                <p class="text-xs font-sans text-emerald-200/90 mt-1 leading-relaxed">{{ session('contact_success') }}</p>
            </div>
            <button @click="showToast = false" class="text-slate-400 hover:text-white transition p-1 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        @endif

        @if(session('contact_error'))
        <div class="clay-card rounded-2xl p-4 bg-[#1a0a0a]/95 border border-red-500/40 shadow-[0_0_30px_rgba(239,68,68,0.3),inset_1px_1px_4px_rgba(255,255,255,0.1)] backdrop-blur-xl flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-500/20 border border-red-400/50 flex items-center justify-center text-red-400 flex-shrink-0 shadow-[0_0_15px_rgba(239,68,68,0.4)]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="font-heading font-bold text-sm text-white flex items-center justify-between">
                    <span>Gagal Mengirim Pesan!</span>
                    <span class="text-[10px] font-mono text-red-400 uppercase tracking-widest bg-red-500/20 px-2 py-0.5 rounded-full border border-red-500/30">Error</span>
                </h4>
                <p class="text-xs font-sans text-red-200/90 mt-1 leading-relaxed">{{ session('contact_error') }}</p>
            </div>
            <button @click="showToast = false" class="text-slate-400 hover:text-white transition p-1 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        @endif

        @if($errors->any() && !session('contact_error'))
        <div class="clay-card rounded-2xl p-4 bg-[#1a0a0a]/95 border border-red-500/40 shadow-[0_0_30px_rgba(239,68,68,0.3),inset_1px_1px_4px_rgba(255,255,255,0.1)] backdrop-blur-xl flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-500/20 border border-red-400/50 flex items-center justify-center text-red-400 flex-shrink-0 shadow-[0_0_15px_rgba(239,68,68,0.4)]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="font-heading font-bold text-sm text-white flex items-center justify-between">
                    <span>Pesan Gagal Terkirim</span>
                    <span class="text-[10px] font-mono text-red-400 uppercase tracking-widest bg-red-500/20 px-2 py-0.5 rounded-full border border-red-500/30">Validasi</span>
                </h4>
                <p class="text-xs font-sans text-red-200/90 mt-1">Harap periksa dan lengkapi kolom form yang belum sesuai.</p>
            </div>
            <button @click="showToast = false" class="text-slate-400 hover:text-white transition p-1 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        @endif

    </div>
    @endif

    <!-- Swarm Cursor Root -->
    <div id="react-swarm-cursor-root" class="fixed inset-0 z-[5] pointer-events-none"></div>
    <!-- 3D Background Canvas -->
    <div id="react-id-card-root" class="absolute top-0 left-0 w-full h-screen z-10 opacity-100 pointer-events-auto" data-name="{{ $firstName . ' ' . $lastName }}" data-headline="{{ $user->headline }}" data-avatar="{{ $user->avatar ? Storage::url($user->avatar) : 'null' }}"></div>
    <div class="lanyard-loading-card" data-id-card-fallback aria-hidden="true">
        <span class="lanyard-loading-card__strap"></span>
        <img src="{{ asset('images/gambar-depan.webp') }}" alt="" fetchpriority="high" decoding="async">
    </div>

    <!-- Fixed Navigation Menu -->
    <div class="fixed top-0 left-0 right-0 z-50 flex justify-center p-4 transition-all duration-300">
        <nav x-data="{ 
                 mobileMenuOpen: false, 
                 scrolled: false, 
                 activeSection: 'beranda',
                 updateActiveSection() {
                     if (this.mobileMenuOpen) return;
                     this.scrolled = (window.pageYOffset > 20);
                     const sections = ['kontak', 'karier', 'proyek-preview', 'skill', 'beranda'];
                     const scrollPos = window.pageYOffset + 250;
                     for (const sec of sections) {
                         const el = document.getElementById(sec);
                         if (el && scrollPos >= el.offsetTop) {
                             this.activeSection = sec;
                             break;
                         }
                     }
                 }
             }" 
             x-effect="document.body.style.overflow = mobileMenuOpen ? 'hidden' : ''"
             x-init="updateActiveSection(); if (window.location.hash) { activeSection = window.location.hash.replace('#', ''); }"
             @scroll.window="updateActiveSection()"
             :class="scrolled ? 'glass-nav py-2.5 sm:py-3 px-4 sm:px-8 rounded-full shadow-2xl max-w-6xl w-full' : 'bg-transparent py-4 sm:py-5 px-4 w-full'"
             class="transition-all duration-300 flex justify-between items-center max-w-7xl mx-auto">
            <!-- Logo -->
            <a href="#beranda" @click="activeSection = 'beranda'" class="font-heading text-xl sm:text-2xl font-bold tracking-widest uppercase text-white cursor-pointer hover:opacity-85 transition shrink-0">
                {{ ucfirst(strtolower(explode(' ', $user->name)[0] ?? 'Rayhan')) }}<span class="text-accentlight" style="text-shadow: 0 0 8px rgba(212,175,55,0.4);">.</span>
            </a>
            
            <!-- Right Container: Desktop Links + Actions -->
            <div class="flex items-center gap-6 sm:gap-8 ml-auto">
                <!-- Desktop Links (Right-aligned next to Download CV) -->
                <ul class="hidden md:flex gap-6 lg:gap-8 text-xs uppercase tracking-[0.15em] text-gray-400 font-medium items-center">
                    <li>
                        <a href="{{ route('home') }}#beranda" 
                           @click="activeSection = 'beranda'"
                           :class="activeSection === 'beranda' ? 'text-accentlight font-bold active' : 'text-gray-400 hover:text-accentlight'"
                           class="nav-link-futuristic transition duration-200 cursor-pointer">Beranda</a>
                    </li>
                    <li><a href="{{ route('projects.index') }}" class="nav-link-futuristic hover:text-accentlight transition duration-200 cursor-pointer">Proyek</a></li>
                    <li><a href="{{ route('certificates.index') }}" class="nav-link-futuristic hover:text-accentlight transition duration-200 cursor-pointer">Sertifikat</a></li>
                    <li>
                        <a href="{{ route('home') }}#kontak" 
                           @click="activeSection = 'kontak'"
                           :class="activeSection === 'kontak' ? 'text-accentlight font-bold active' : 'text-gray-400 hover:text-accentlight'"
                           class="nav-link-futuristic transition duration-200 cursor-pointer">Kontak</a>
                    </li>
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
                         class="fixed top-0 right-0 bottom-0 w-72 sm:w-80 bg-[#0a0d18]/98 backdrop-blur-2xl border-l border-amber-500/20 shadow-2xl z-[9991] flex flex-col justify-between p-6 overflow-y-auto md:hidden">
                        
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
                                    <a @click="mobileMenuOpen = false; activeSection = 'beranda'" 
                                       href="{{ route('home') }}#beranda" 
                                       :class="activeSection === 'beranda' ? 'text-amber-400 bg-amber-400/10 border-l-2 border-amber-400 font-bold pl-4 py-3' : 'hover:text-amber-400 pl-4 py-3 hover:bg-white/5'"
                                       class="transition flex items-center rounded-r-lg cursor-pointer">
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
                                       class="transition flex items-center pl-4 py-3 hover:text-amber-400 hover:bg-white/5 rounded-r-lg cursor-pointer">
                                        <svg class="w-4 h-4 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                                        Sertifikat
                                    </a>
                                </li>
                                <li>
                                    <a @click="mobileMenuOpen = false; activeSection = 'kontak'" 
                                       href="{{ route('home') }}#kontak" 
                                       :class="activeSection === 'kontak' ? 'text-amber-400 bg-amber-400/10 border-l-2 border-amber-400 font-bold pl-4 py-3' : 'hover:text-amber-400 pl-4 py-3 hover:bg-white/5'"
                                       class="transition flex items-center rounded-r-lg cursor-pointer">
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

    <!-- SECTION: BERANDA -->
    <header id="beranda" class="bg-darker futuristic-grid relative min-h-screen flex flex-col justify-center px-4 sm:px-6 md:px-12 pt-24 sm:pt-28 lg:pt-24 pb-16 overflow-hidden pointer-events-none">
        <!-- Huge Background Text -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-0">
            <h1 class="font-heading font-bold text-[20vw] text-accent opacity-[0.04] leading-none whitespace-nowrap select-none" style="transform: translateY(-5%); text-shadow: 0 0 80px rgba(212,175,55,0.08);">PORTFOLIO</h1>
        </div>
        
        <div class="relative z-30 w-full max-w-7xl mx-auto flex flex-col lg:flex-row items-center justify-between pointer-events-none pt-[270px] sm:pt-[300px] lg:pt-0">
            <div class="w-full lg:w-3/5 flex flex-col items-center lg:items-start text-center lg:text-left pointer-events-auto">

                <!-- Greeting "Hello, I'm" (Left aligned on mobile, sparkles removed) -->
                <div class="w-full text-left pl-2 sm:pl-4 lg:pl-0 mb-1">
                    <span style="font-family: var(--font-script);" class="neon-text-gold text-amber-300 text-2xl sm:text-3xl lg:text-5xl inline-block font-normal tracking-wide">
                        Hello, I'm
                    </span>
                </div>

                <!-- Full Name (Centered on mobile) -->
                <h1 class="font-heading text-4xl sm:text-5xl lg:text-[5.5rem] font-extrabold leading-[1.05] tracking-tight mb-2 text-white uppercase text-center lg:text-left w-full" style="text-shadow: 0 0 40px rgba(212,175,55,0.15), 0 0 80px rgba(212,175,55,0.05);">
                    {{ $firstName }}<br class="sm:hidden" /> {{ $lastName }}<span class="text-amber-400">.</span>
                </h1>

                <!-- Headline / Role (Centered on mobile with gold line accents) -->
                @if($user->headline)
                <div class="flex items-center justify-center lg:justify-start gap-2.5 sm:gap-3 w-full mb-4">
                    <span class="w-6 sm:w-10 h-[1px] bg-gradient-to-r from-transparent via-amber-400/60 to-amber-400"></span>
                    <h2 class="text-amber-400 font-sans font-bold tracking-[0.15em] sm:tracking-[0.2em] uppercase text-xs sm:text-sm text-center lg:text-left">
                        {{ $user->headline }}
                    </h2>
                    <span class="w-6 sm:w-10 h-[1px] bg-gradient-to-l from-transparent via-amber-400/60 to-amber-400"></span>
                </div>
                @endif

                <!-- Bio Description (Centered on mobile) -->
                <p class="text-gray-300 text-xs sm:text-sm md:text-[15px] leading-[1.8] mb-6 max-w-lg mx-auto lg:mx-0 font-sans font-light text-center lg:text-left">
                    {{ $user->bio ?? 'Mahasiswa Sistem Informasi UBSI yang sedang membangun skill sebagai Full Stack Developer & UI/UX Designer lewat proyek-proyek nyata. Suka eksplorasi teknologi baru dan menjadikan AI sebagai partner belajar, bukan sekadar alat ngoding.' }}
                </p>

                <!-- Action Buttons & Social Links Container (Side-by-side on Desktop, stacked on Mobile) -->
                <div class="w-full flex flex-col lg:flex-row lg:items-center gap-5 lg:gap-3.5 mb-8 lg:mb-12">
                    <!-- Download CV Button -->
                    <div class="w-full lg:w-auto flex justify-center lg:justify-start shrink-0">
                        @if(Route::has('cv.download'))
                        <a href="{{ route('cv.download') }}" class="group clay-btn bg-gradient-to-r from-amber-500 via-amber-400 to-amber-500 hover:from-amber-400 hover:to-amber-300 text-slate-950 font-sans font-bold uppercase tracking-wider text-xs sm:text-sm px-7 py-3 sm:px-8 sm:py-3.5 rounded-full shadow-[0_0_25px_rgba(212,175,55,0.4)] flex items-center justify-center gap-2 cursor-pointer transition-all duration-300 hover:scale-105 active:scale-95">
                            <svg class="w-4 h-4 transform group-hover:translate-y-0.5 transition-transform text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            <span>DOWNLOAD CV</span>
                        </a>
                        @else
                        <a href="{{ $user->cv_file ? Storage::url($user->cv_file) : '#' }}" target="_blank" class="group clay-btn bg-gradient-to-r from-amber-500 via-amber-400 to-amber-500 hover:from-amber-400 hover:to-amber-300 text-slate-950 font-sans font-bold uppercase tracking-wider text-xs sm:text-sm px-7 py-3 sm:px-8 sm:py-3.5 rounded-full shadow-[0_0_25px_rgba(212,175,55,0.4)] flex items-center justify-center gap-2 cursor-pointer transition-all duration-300 hover:scale-105 active:scale-95">
                            <svg class="w-4 h-4 transform group-hover:translate-y-0.5 transition-transform text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            <span>DOWNLOAD CV</span>
                        </a>
                        @endif
                    </div>

                    <!-- Vertical Divider Line (Desktop Only) -->
                    <div class="hidden lg:block w-px h-8 bg-white/15 shrink-0 mx-1"></div>

                    <!-- Social Logos & Glowing Line Dividers -->
                    <div class="w-full lg:w-auto flex items-center justify-center lg:justify-start gap-3 sm:gap-4 max-w-md mx-auto lg:mx-0 lg:max-w-none shrink-0">
                        <!-- Left Glowing Line (Mobile Only) -->
                        <div class="h-[1px] flex-1 bg-gradient-to-r from-transparent via-purple-500/40 to-amber-400/60 relative lg:hidden">
                            <span class="absolute right-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-amber-400 shadow-[0_0_8px_rgba(212,175,55,0.8)]"></span>
                        </div>

                        <!-- Social Links Component -->
                        <x-social-links :links="$user->socialLinks ?? null" 
                                        itemClass="clay-card w-11 h-11 rounded-xl border border-amber-400/30 bg-[#0c1022]/90 backdrop-blur-md flex items-center justify-center text-amber-300 hover:text-white hover:border-amber-400 hover:shadow-[0_0_15px_rgba(212,175,55,0.4)] transition-all duration-300 hover:scale-110 cursor-pointer" />

                        <!-- Right Glowing Line (Mobile Only) -->
                        <div class="h-[1px] flex-1 bg-gradient-to-l from-transparent via-purple-500/40 to-amber-400/60 relative lg:hidden">
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-amber-400 shadow-[0_0_8px_rgba(212,175,55,0.8)]"></span>
                        </div>
                    </div>
                </div>

                <!-- Stat Cards Grid (Mobile View) -->
                <div class="w-full max-w-md mx-auto grid grid-cols-3 divide-x divide-amber-500/20 mt-2 lg:hidden">
                    <!-- Stat 1: Experience -->
                    <div class="px-2 py-3 text-center flex flex-col items-center justify-center">
                        <div class="text-amber-400 mb-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <h3 class="font-heading font-extrabold text-2xl sm:text-3xl text-amber-400 neon-text-gold leading-none mb-1">
                            {{ $yearsExperience }}+
                        </h3>
                        <p class="text-[8px] sm:text-[9px] font-sans font-bold uppercase tracking-wider text-gray-400 leading-tight">
                            YEARS<br/>EXPERIENCE
                        </p>
                    </div>

                    <!-- Stat 2: Projects -->
                    <div class="px-2 py-3 text-center flex flex-col items-center justify-center">
                        <div class="text-amber-400 mb-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="font-heading font-extrabold text-2xl sm:text-3xl text-amber-400 neon-text-gold leading-none mb-1">
                            {{ $totalProjects }}+
                        </h3>
                        <p class="text-[8px] sm:text-[9px] font-sans font-bold uppercase tracking-wider text-gray-400 leading-tight">
                            PROJECTS<br/>COMPLETED
                        </p>
                    </div>

                    <!-- Stat 3: Certificates -->
                    <div class="px-2 py-3 text-center flex flex-col items-center justify-center">
                        <div class="text-amber-400 mb-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-heading font-extrabold text-2xl sm:text-3xl text-amber-400 neon-text-gold leading-none mb-1">
                            {{ $totalCertificates }}+
                        </h3>
                        <p class="text-[8px] sm:text-[9px] font-sans font-bold uppercase tracking-wider text-gray-400 leading-tight">
                            CERTIFICATES
                        </p>
                    </div>
                </div>

            </div>
            
            <!-- Stats (Desktop: absolute right side stacked) -->
            <div class="hidden lg:flex flex-col gap-6 text-left absolute right-0">
                <div class="border-l border-amber-500/25 pl-4 py-2 flex items-center gap-4">
                    <div class="text-accentlight">
                        <svg class="w-5 h-5 text-accentlight" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-accentlight neon-text-gold font-heading text-4xl font-bold mb-0">{{ $yearsExperience }}+</h3>
                        <p class="text-gray-500 text-[9px] font-sans font-bold uppercase tracking-widest leading-relaxed">TAHUN PENGALAMAN</p>
                    </div>
                </div>
                <div class="border-l border-amber-500/25 pl-4 py-2 flex items-center gap-4">
                    <div class="text-accentlight">
                        <svg class="w-5 h-5 text-accentlight" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-accentlight neon-text-gold font-heading text-4xl font-bold mb-0">{{ $totalProjects }}+</h3>
                        <p class="text-gray-500 text-[9px] font-sans font-bold uppercase tracking-widest leading-relaxed">PROYEK SELESAI</p>
                    </div>
                </div>
                <div class="border-l border-amber-500/25 pl-4 py-2 flex items-center gap-4">
                    <div class="text-accentlight">
                        <svg class="w-5 h-5 text-accentlight" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-accentlight neon-text-gold font-heading text-4xl font-bold mb-0">{{ $totalCertificates }}+</h3>
                        <p class="text-gray-500 text-[9px] font-sans font-bold uppercase tracking-widest leading-relaxed">SERTIFIKAT</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- SECTION: SKILL -->
    <section id="skill" class="px-6 md:px-12 py-8 md:py-10 relative flex flex-col items-center justify-center overflow-hidden before:absolute before:inset-0 before:bg-darker before:z-[-1]">
        


        <!-- Header Showcase Area (Matching Proyek, Sertifikat, & Karier Header Style) -->
        <div class="max-w-7xl mx-auto w-full mb-4 md:mb-6 relative z-20">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-6 sm:gap-8">
                
                <!-- Left Column: Title & Subtitle (Centered on mobile) -->
                <div class="w-full lg:w-1/2 flex flex-col items-center lg:items-start text-center lg:text-left">
                    <h2 class="font-heading text-3xl sm:text-4xl md:text-5xl text-white font-bold tracking-tight mb-3 text-center lg:text-left">
                        Teknologi &amp; <span class="text-accentlight neon-text-gold">Keahlian.</span>
                    </h2>

                    <p class="text-slate-400 font-sans text-xs md:text-sm max-w-md mx-auto lg:mx-0 leading-relaxed mb-0 text-center lg:text-left">
                        Perangkat lunak, bahasa pemrograman, dan kerangka kerja utama yang dikuasai dalam membangun solusi digital modern.
                    </p>
                </div>

                <!-- Right Column: Average Skill Percentage Widget Box (Desktop Only) -->
                <div class="hidden lg:flex w-full lg:w-1/2 items-center justify-end">
                    <div class="clay-card inline-flex items-center gap-4 p-3.5 px-5 rounded-2xl border border-amber-400/30 shadow-[0_0_20px_rgba(212,175,55,0.15)]">
                        <div class="clay-circle w-11 h-11 flex items-center justify-center text-amber-300 flex-shrink-0">
                            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        
                        <div class="text-left">
                            <span class="block text-[9px] font-mono uppercase tracking-[0.2em] text-amber-400 font-bold mb-0.5">PERSENTASE KEAHLIAN</span>
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-2xl font-bold text-white font-heading">{{ (int) round($groupedSkills->flatten()->avg('level') ?? 0) }}%</span>
                                <span class="text-slate-400 font-mono text-xs">Rata-rata Kuasai</span>
                            </div>
                        </div>

                        <!-- Mini 3D Glowing Bar Chart Visualization (Gold & Amber) -->
                        <div class="clay-card flex items-end gap-1.5 h-8 px-2.5 py-1.5 rounded-xl border border-amber-400/20 ml-2">
                            <div class="w-1.5 bg-gradient-to-t from-amber-700 to-amber-400 rounded-t-sm h-5 shadow-[0_0_6px_rgba(212,175,55,0.4)]"></div>
                            <div class="w-1.5 bg-gradient-to-t from-amber-600 to-amber-300 rounded-t-sm h-3 shadow-[0_0_6px_rgba(251,191,36,0.4)]"></div>
                            <div class="w-1.5 bg-gradient-to-t from-amber-500 to-amber-200 rounded-t-sm h-7 shadow-[0_0_8px_rgba(251,191,36,0.6)]"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Central Floating Showcase (Scattered Layout) -->
        <div class="relative w-full max-w-6xl flex-1 min-h-[400px] md:min-h-[500px] max-h-[600px] flex items-center justify-center z-20 scale-75 sm:scale-90 lg:scale-100 mt-2">
            
            <!-- Podium 3D base -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-[20%] w-[380px] h-[120px] sm:w-[480px] sm:h-[140px] podium-3d z-10">
            </div>


            
            <!-- Dynamic Percentage Text (Center) -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 flex flex-col items-center justify-center mt-[-30px]" style="z-index: 30;">
                <div class="absolute -top-12 -left-8 text-accent animate-pulse">
                    <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L13.5 9.5L21 11L13.5 12.5L12 20L10.5 12.5L3 11L10.5 9.5L12 2Z"/></svg>
                </div>
                <div id="dynamic-percentage" class="font-heading font-black flex items-start leading-none" style="filter: drop-shadow(0 20px 30px rgba(0,0,0,0.9));">
                    <span id="sun-skill-level" class="text-8xl sm:text-[180px] tracking-tighter" style="background: linear-gradient(180deg, #fff 0%, #d4af37 60%, #92400e 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; text-shadow: 0 4px 20px rgba(212,175,55,0.5);">0</span>
                    <span class="text-5xl sm:text-[80px] mt-2 sm:mt-6 font-bold" style="background: linear-gradient(180deg, #fff 0%, #d4af37 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">%</span>
                </div>
                
                <div id="sun-skill-name" class="mt-3 font-sans text-xs font-bold tracking-widest text-accentlight uppercase opacity-0 transition-opacity duration-300">KEAHLIAN</div>
            </div>

            @php
                // Flatten and get all skills for the scattered layout
                $allPlanetSkills = collect();
                foreach ($groupedSkills as $cat => $skills) {
                    $allPlanetSkills = $allPlanetSkills->merge($skills);
                }
                
                // Limit to max 20 to avoid extreme overcrowding, but accommodate a dynamic amount
                $maxDisplay = min(20, $allPlanetSkills->count());
                $topSkills = $allPlanetSkills->take($maxDisplay);
                
                // Generate dynamic scattered positions
                $positions = [];
                foreach ($topSkills as $idx => $skill) {
                    // Spread them out along an ellipse
                    $angle = ($idx / $maxDisplay) * pi() * 2;
                    
                    // Jitter the radius to scatter them (inner vs outer band)
                    $isInner = ($idx % 2 == 0);
                    // Safe zone to not block the center text (which is around 50% +/- 25% horizontal)
                    $radX = $isInner ? 30 + ($idx % 7) : 42 + ($idx % 7);
                    $radY = $isInner ? 32 + (($idx * 3) % 7) : 44 + (($idx * 2) % 5);
                    
                    $centerX = 50;
                    $centerY = 45;
                    
                    $left = $centerX + ($radX * cos($angle));
                    $top = $centerY + ($radY * sin($angle));
                    
                    // Determine Size based on Level
                    $level = $skill->level ?? 50;
                    if ($level >= 90) {
                        $sizeClass = "w-[90px] h-[90px] sm:w-[120px] sm:h-[120px] p-3";
                        $imgClass = "w-12 h-12 sm:w-16 sm:h-16 mb-2";
                        $textClass = "text-[11px] sm:text-sm";
                    } elseif ($level >= 75) {
                        $sizeClass = "w-[80px] h-[80px] sm:w-[100px] sm:h-[100px] p-2";
                        $imgClass = "w-10 h-10 sm:w-14 sm:h-14 mb-1.5";
                        $textClass = "text-[10px] sm:text-xs";
                    } elseif ($level >= 50) {
                        $sizeClass = "w-[70px] h-[70px] sm:w-[85px] sm:h-[85px] p-1.5";
                        $imgClass = "w-8 h-8 sm:w-10 sm:h-10 mb-1";
                        $textClass = "text-[9px] sm:text-[10px]";
                    } else {
                        $sizeClass = "w-[60px] h-[60px] sm:w-[70px] sm:h-[70px] p-1";
                        $imgClass = "w-6 h-6 sm:w-8 sm:h-8 mb-1";
                        $textClass = "text-[8px] sm:text-[9px]";
                    }

                    // Determine dynamic tooltip placement based on proximity to edges
                    $distLeft = $left;
                    $distRight = 100 - $left;
                    $distTop = $top;
                    $distBottom = 100 - $top;
                    
                    $minDist = min($distLeft, $distRight, $distTop, $distBottom);
                    
                    $tooltipClass = '';
                    if ($minDist == $distLeft) {
                        $tooltipClass = 'left-full top-1/2 -translate-y-1/2 ml-3'; // closest to left edge, open right
                    } elseif ($minDist == $distRight) {
                        $tooltipClass = 'right-full top-1/2 -translate-y-1/2 mr-3'; // closest to right edge, open left
                    } elseif ($minDist == $distTop) {
                        $tooltipClass = 'top-full left-1/2 -translate-x-1/2 mt-3'; // closest to top edge, open bottom
                    } else {
                        $tooltipClass = 'bottom-full left-1/2 -translate-x-1/2 mb-3'; // closest to bottom edge, open top
                    }
                    
                    $durations = ['4.8s', '5.6s', '6.4s', '7.2s', '5.2s', '6.8s', '5.0s', '6.0s'];
                    $delays = ['0s', '-1.6s', '-3.2s', '-4.8s', '-2.4s', '-0.8s', '-3.8s', '-2.0s'];

                    $positions[] = [
                        'top' => $top . '%',
                        'left' => $left . '%',
                        'anim' => 'float-' . (($idx % 6) + 1),
                        'duration' => $durations[$idx % count($durations)],
                        'delay' => $delays[$idx % count($delays)],
                        'sizeClass' => $sizeClass,
                        'imgClass' => $imgClass,
                        'textClass' => $textClass,
                        'tooltipClass' => $tooltipClass
                    ];
                }
            @endphp
            
            <!-- Floating Scattered Elements -->
            @foreach ($topSkills as $idx => $skill)
            @php
                $iconUrl = $skill->icon ? Storage::url($skill->icon) : null;
                $pos = $positions[$idx];
                
                // Use database description if available, otherwise fallback
                $desc = $skill->description ?: "Familiar and experienced in utilizing {$skill->name} to build modern applications with a proficiency level of {$skill->level}%.";
            @endphp
            <div class="absolute z-40 skill-node"
                 style="top: {{ $pos['top'] }}; left: {{ $pos['left'] }}; transform: translate(-50%, -50%);">
                 <div class="relative cursor-pointer" onclick="toggleSkillModal(this)" style="animation: {{ $pos['anim'] }} {{ $pos['duration'] }} ease-in-out {{ $pos['delay'] }} infinite;">
                    
                    <!-- Icon Box -->
                    <div class="clay-icon-3d {{ $pos['sizeClass'] }} hover:border-accent/50 transition-colors duration-300">
                         @if($iconUrl)
                            <img src="{{ $iconUrl }}" alt="{{ $skill->name }}" class="{{ $pos['imgClass'] }} object-contain drop-shadow-xl pointer-events-none" loading="lazy">
                         @else
                            <span class="text-accentlight font-heading font-bold text-xl mb-1.5 pointer-events-none">{{ strtoupper(substr($skill->name, 0, 2)) }}</span>
                         @endif
                         <span class="{{ $pos['textClass'] }} font-sans font-bold text-slate-300 text-center leading-tight truncate w-full px-1 pointer-events-none">{{ $skill->name }}</span>
                    </div>

                    <!-- Clickable Modal / Tooltip -->
                    <div class="skill-modal hidden absolute w-48 md:w-56 p-4 bg-slate-900/95 border border-slate-700/80 rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.8)] backdrop-blur-xl z-50 {{ $pos['tooltipClass'] }}" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-accent font-bold text-sm md:text-base">{{ $skill->name }}</h4>
                            <span class="text-[10px] bg-accent/20 text-accentlight px-2 py-0.5 rounded-full">{{ $skill->level }}%</span>
                        </div>
                        <p class="text-[10px] md:text-xs text-slate-400 leading-relaxed">
                            {{ $desc }}
                        </p>
                        <!-- Close button for mobile or clarity -->
                        <button class="absolute -top-2 -right-2 w-6 h-6 bg-slate-800 border border-slate-600 rounded-full text-slate-400 hover:text-white flex items-center justify-center text-xs shadow-lg" onclick="this.closest('.skill-node').style.zIndex='40'; this.closest('.skill-modal').classList.add('hidden'); event.stopPropagation();">✕</button>
                    </div>

                 </div>
            </div>
            @endforeach
            
            <script>
            function toggleSkillModal(element) {
                const node = element.closest('.skill-node');
                
                // Hide all other modals and reset z-index
                document.querySelectorAll('.skill-modal').forEach(el => {
                    if (el !== element.querySelector('.skill-modal')) {
                        el.classList.add('hidden');
                        el.closest('.skill-node').style.zIndex = '40';
                    }
                });
                
                // Toggle current modal and z-index
                const modal = element.querySelector('.skill-modal');
                if (modal.classList.contains('hidden')) {
                    modal.classList.remove('hidden');
                    node.style.zIndex = '100'; // bring to front
                } else {
                    modal.classList.add('hidden');
                    node.style.zIndex = '40'; // reset
                }
            }
            
            // Close on outside click
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.skill-node')) {
                    document.querySelectorAll('.skill-modal').forEach(el => {
                        el.classList.add('hidden');
                        el.closest('.skill-node').style.zIndex = '40';
                    });
                }
            });
            
            (function() {
                var skills = @json($allPlanetSkills->map(fn($s) => ['name' => $s->name, 'level' => $s->level ?? 0])->values());
                if(skills.length === 0) return;
                
                var lastIdx = -1;
                var levelEl = document.getElementById('sun-skill-level');
                var nameEl = document.getElementById('sun-skill-name');
                var container = document.getElementById('dynamic-percentage');

                function showNext() {
                    if (!levelEl || !nameEl) return;
                    
                    var nextIdx;
                    if (skills.length > 1) {
                        do {
                            nextIdx = Math.floor(Math.random() * skills.length);
                        } while (nextIdx === lastIdx);
                    } else {
                        nextIdx = 0;
                    }
                    lastIdx = nextIdx;
                    var skill = skills[nextIdx];

                    if (container) {
                        container.style.transition = 'opacity 0.15s ease, transform 0.15s ease';
                        container.style.opacity = '0';
                        container.style.transform = 'scale(0.92)';
                    }
                    nameEl.style.transition = 'opacity 0.15s ease';
                    nameEl.style.opacity = '0';

                    setTimeout(function() {
                        levelEl.innerText = skill.level;
                        nameEl.innerText = skill.name;
                        
                        if (container) {
                            container.style.opacity = '1';
                            container.style.transform = 'scale(1)';
                        }
                        nameEl.style.opacity = '1';
                    }, 150);
                }

                function startLoop() {
                    showNext();
                    setInterval(showNext, 1500);
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', startLoop);
                } else {
                    startLoop();
                }
            })();
            </script>
        </div>

        <!-- Bottom Showcase Area: Average Skill Percentage Widget Box (Mobile Only - Positioned below floating skill icons) -->
        <div class="w-full flex justify-center mt-6 sm:mt-8 mb-4 relative z-30 pointer-events-auto lg:hidden">
            <div class="clay-card inline-flex items-center gap-3 sm:gap-4 p-3 px-4 sm:p-3.5 sm:px-5 rounded-2xl border border-amber-400/30 shadow-[0_0_25px_rgba(212,175,55,0.2)] bg-[#0c1022]/95 backdrop-blur-xl">
                <div class="clay-circle w-10 h-10 sm:w-11 sm:h-11 flex items-center justify-center text-amber-300 flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                
                <div class="text-left">
                    <span class="block text-[8px] sm:text-[9px] font-mono uppercase tracking-[0.2em] text-amber-400 font-bold mb-0.5">PERSENTASE KEAHLIAN</span>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-xl sm:text-2xl font-bold text-white font-heading">{{ (int) round($groupedSkills->flatten()->avg('level') ?? 0) }}%</span>
                        <span class="text-slate-400 font-mono text-[11px] sm:text-xs">Rata-rata Kuasai</span>
                    </div>
                </div>

                <!-- Mini 3D Glowing Bar Chart Visualization (Gold & Amber) -->
                <div class="clay-card flex items-end gap-1.5 h-8 px-2.5 py-1.5 rounded-xl border border-amber-400/20 ml-1 sm:ml-2">
                    <div class="w-1.5 bg-gradient-to-t from-amber-700 to-amber-400 rounded-t-sm h-5 shadow-[0_0_6px_rgba(212,175,55,0.4)]"></div>
                    <div class="w-1.5 bg-gradient-to-t from-amber-600 to-amber-300 rounded-t-sm h-3 shadow-[0_0_6px_rgba(251,191,36,0.4)]"></div>
                    <div class="w-1.5 bg-gradient-to-t from-amber-500 to-amber-200 rounded-t-sm h-7 shadow-[0_0_8px_rgba(251,191,36,0.6)]"></div>
                </div>
            </div>
        </div>
    </section>

    @php
        $experienceData = $experiences->map(function($e) {
            $start = \Carbon\Carbon::parse($e->start_date);
            $end = $e->is_current ? now() : ($e->end_date ? \Carbon\Carbon::parse($e->end_date) : now());
            $months = $start->diffInMonths($end) + 1;
            $durText = $months >= 12 ? floor($months / 12) . ' Tahun ' . ($months % 12 > 0 ? ($months % 12) . ' Bulan' : '') : $months . ' Bulan';
            
            // Handle company logo (could be string or array due to cast changes)
            $logoUrl = null;
            if ($e->company_logo) {
                if (is_array($e->company_logo) && count($e->company_logo) > 0) {
                    $logoUrl = Storage::disk('public')->url(is_string(array_key_first($e->company_logo)) ? $e->company_logo[array_key_first($e->company_logo)] : $e->company_logo[0]);
                } else if (is_string($e->company_logo)) {
                    $logoUrl = Storage::disk('public')->url($e->company_logo);
                }
            }

            // Handle documentation photos
            $docs = [];
            if ($e->documentation_photos && is_array($e->documentation_photos)) {
                foreach ($e->documentation_photos as $photo) {
                    $docs[] = Storage::disk('public')->url($photo);
                }
            }
            
            return [
                'title' => $e->title,
                'company' => $e->company,
                'logo' => $logoUrl,
                'location' => $e->location,
                'type' => $e->type ?? 'Full-time',
                'start' => $start->format('M Y'),
                'end' => $e->is_current ? 'Present' : ($e->end_date ? \Carbon\Carbon::parse($e->end_date)->format('M Y') : 'N/A'),
                'duration' => $durText,
                'desc' => $e->description,
                'photos' => $docs,
            ];
        })->values();
    @endphp
    <!-- SECTION: PENGALAMAN -->
    <section id="pengalaman" class="px-4 sm:px-6 md:px-12 py-8 md:py-10 relative overflow-hidden bg-darker futuristic-grid before:absolute before:inset-0 before:bg-darker before:z-[-1]" x-data="{ 
        activeExp: 0, 
        experiences: {{ json_encode($experienceData) }},
        modalOpen: false,
        modalImages: [],
        modalIndex: 0,
        openModal(photos, index) {
            this.modalImages = photos;
            this.modalIndex = index;
            this.modalOpen = true;
        },
        nextImage() {
            if(this.modalIndex < this.modalImages.length - 1) this.modalIndex++;
        },
        prevImage() {
            if(this.modalIndex > 0) this.modalIndex--;
        }
    }">
        <div class="relative z-10 max-w-7xl mx-auto">
            <!-- Hero Header Showcase Area (2 Columns - Matching Proyek & Sertifikat Header Style) -->
            <div class="flex flex-col lg:flex-row items-center justify-between gap-6 mb-4 md:mb-6 relative z-10">
                
                <!-- Left Column: Title & Subtitle (Centered on Mobile, Left-aligned on Desktop) -->
                <div class="w-full lg:w-1/2 flex flex-col items-center lg:items-start text-center lg:text-left">
                    <h2 class="font-heading text-3xl sm:text-4xl md:text-5xl text-white font-bold tracking-tight mb-3 sm:mb-4">
                        Perjalanan <span class="text-accentlight neon-text-gold">Karier.</span>
                    </h2>

                    <p class="text-slate-400 font-sans text-xs md:text-sm max-w-md leading-relaxed mb-0">
                        Rekam jejak profesional, peran kepemimpinan, dan kontribusi nyata dalam industri teknologi.
                    </p>
                </div>

                <!-- Right Column: 3D Floating Career Chrono Orbit Graphic -->
                <div class="w-full lg:w-1/2 flex items-center justify-center relative min-h-[130px] sm:min-h-[200px] pointer-events-none select-none my-1 lg:my-0">
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-0">
                        <div class="w-36 h-36 sm:w-48 sm:h-48 bg-amber-500/10 rounded-full blur-[80px]"></div>
                    </div>

                    <!-- Orbit Rings -->
                    <div class="absolute w-[190px] h-[65px] sm:w-[300px] sm:h-[100px] rounded-[50%] border border-amber-400/25 pointer-events-none z-0" style="transform: rotate(-12deg); box-shadow: 0 0 25px rgba(212,175,55,0.1);">
                        <div class="absolute top-2 right-8 w-2 h-2 sm:w-3 sm:h-3 rounded-full bg-gradient-to-br from-amber-300 to-amber-500 shadow-[0_0_10px_#f59e0b]"></div>
                        <div class="absolute bottom-2 left-10 w-2.5 h-2.5 sm:w-3.5 sm:h-3.5 rounded-full bg-gradient-to-br from-amber-300 to-amber-500 shadow-[0_0_12px_rgba(212,175,55,0.8)]"></div>
                    </div>

                    <!-- 3D Clay Career Badge / Briefcase Floating Showcase -->
                    <div class="relative z-10 flex flex-col items-center justify-center w-full py-1 [perspective:1000px]">
                        <div class="relative z-20 w-36 h-22 sm:w-56 sm:h-34 rounded-[18px] sm:rounded-[22px] bg-gradient-to-br from-[#1c1f33] via-[#101324] to-[#070914] border-2 border-amber-400/40 p-2.5 sm:p-4 shadow-[-14px_18px_35px_rgba(0,0,0,0.95),inset_2px_2px_8px_rgba(255,255,255,0.15),0_0_25px_rgba(212,175,55,0.2)] flex items-center justify-between pointer-events-auto transition-transform duration-700 hover:scale-105"
                             style="transform: rotateX(18deg) rotateY(-15deg) rotateZ(4deg);">
                            <div class="space-y-1.5 sm:space-y-2 w-1/2 pr-2">
                                <div class="w-[90%] h-1.5 sm:h-2 rounded-full bg-[#0a0c16] border border-amber-400/20 shadow-inner"></div>
                                <div class="w-[70%] h-1.5 sm:h-2 rounded-full bg-[#0a0c16] border border-amber-400/20 shadow-inner"></div>
                                <div class="w-[50%] h-1.5 sm:h-2 rounded-full bg-[#0a0c16] border border-amber-400/20 shadow-inner"></div>
                            </div>
                            <div class="w-9 h-9 sm:w-14 sm:h-14 rounded-lg sm:rounded-xl bg-gradient-to-tr from-amber-500/20 to-amber-400/10 border border-amber-400/40 flex items-center justify-center text-amber-300 shadow-[0_0_15px_rgba(212,175,55,0.3)] flex-shrink-0">
                                <svg class="w-4 h-4 sm:w-7 sm:h-7 text-amber-400 drop-shadow-[0_0_6px_rgba(212,175,55,0.8)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div x-show="experiences.length > 0">
                <div class="flex flex-col lg:flex-row gap-6 lg:gap-12">
                    <!-- Left Sidebar (Timeline + Tabs + Mobile Accordion Dropdowns) -->
                    <div class="w-full lg:w-[340px] flex-shrink-0 flex flex-col justify-between relative z-10">
                        <div class="relative pl-5 sm:pl-6 space-y-3 sm:space-y-4">
                            <!-- Vertical Timeline Line -->
                            <div class="absolute left-1.5 top-4 bottom-4 w-[2px] bg-slate-800/80 z-0"></div>

                            <template x-for="(exp, index) in experiences" :key="index">
                                <div class="relative group cursor-pointer" @click="activeExp = (activeExp === index ? null : index)">
                                    <!-- Connecting Dot on Vertical Timeline Line -->
                                    <div class="absolute -left-5 sm:-left-6 top-6 -translate-y-1/2 z-10 transition-all duration-300">
                                        <div x-show="activeExp === index" class="w-3.5 h-3.5 rounded-full bg-amber-400 border-2 border-darker shadow-[0_0_12px_rgba(212,175,55,0.9)] animate-pulse"></div>
                                        <div x-show="activeExp !== index" class="w-2.5 h-2.5 rounded-full bg-slate-700 hover:bg-amber-400/60 transition-colors"></div>
                                    </div>

                                    <!-- Tab Card -->
                                    <div class="relative w-full p-3.5 sm:p-4 rounded-2xl transition-all duration-300 cursor-pointer flex items-center justify-between clay-card"
                                         :class="activeExp === index 
                                            ? 'border-accentlight/60 shadow-[0_0_20px_rgba(212,175,55,0.15)] bg-amber-400/10 scale-[1.01] sm:scale-[1.02]' 
                                            : 'hover:border-slate-700/80'">
                                        
                                        <div class="flex items-center gap-3 sm:gap-3.5 min-w-0">
                                            <!-- Logo Box -->
                                            <div class="clay-card w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center flex-shrink-0 transition-all duration-300 overflow-hidden relative border"
                                                 :class="activeExp === index 
                                                    ? 'border-amber-400/50 shadow-[0_0_12px_rgba(212,175,55,0.2)]' 
                                                    : 'border-slate-800'">
                                                <template x-if="exp.logo">
                                                    <img :src="exp.logo" :alt="exp.company"
                                                         class="w-full h-full object-cover scale-[1.12] select-none pointer-events-none rounded-xl"
                                                         oncontextmenu="return false;"
                                                         ondragstart="return false;"
                                                         draggable="false">
                                                </template>
                                                <template x-if="!exp.logo">
                                                    <span class="font-heading font-black text-base sm:text-lg select-none text-accentlight" x-text="exp.company.substring(0,1)"></span>
                                                </template>
                                            </div>
                                            <div class="flex-grow min-w-0 pr-2">
                                                <div class="font-heading font-bold text-xs sm:text-sm truncate text-white" x-text="exp.company"></div>
                                                <div class="text-[10px] sm:text-[11px] text-slate-400 truncate mt-0.5" x-text="exp.title"></div>
                                                <div class="text-[9px] sm:text-[10px] text-accentlight font-medium tracking-wide mt-0.5 sm:mt-1" x-text="exp.start + ' - ' + exp.end"></div>
                                            </div>
                                        </div>

                                        <!-- Chevron Arrow Dropdown Indicator -->
                                        <div class="text-accentlight flex-shrink-0 pr-1 transition-transform duration-300"
                                             :class="activeExp === index ? 'rotate-180 text-amber-400' : 'rotate-0 text-slate-400'">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>

                                    <!-- Mobile Inline Accordion Dropdown Detail Card -->
                                    <div x-show="activeExp === index"
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
                                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                         x-transition:leave="transition ease-in duration-200"
                                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                         x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
                                         class="mt-3 mb-2 p-4 sm:p-5 clay-card rounded-2xl border border-amber-400/40 shadow-[0_0_25px_rgba(212,175,55,0.2)] bg-[#0c1020]/95 lg:hidden relative z-20"
                                         @click.stop>
                                        
                                        <!-- Mobile Detail Header -->
                                        <div class="flex flex-col gap-3 pb-3 border-b border-dashed border-slate-800/80">
                                            <div class="flex items-center gap-3">
                                                <div class="clay-card w-12 h-12 flex items-center justify-center flex-shrink-0 relative overflow-hidden border border-amber-400/40 shadow-[0_0_15px_rgba(212,175,55,0.2)]">
                                                    <template x-if="exp.logo">
                                                        <img :src="exp.logo" :alt="exp.company" class="w-full h-full object-cover scale-[1.12] select-none pointer-events-none rounded-xl">
                                                    </template>
                                                    <template x-if="!exp.logo">
                                                        <span class="text-amber-400 font-heading font-black text-lg select-none" x-text="exp.company.substring(0,1)"></span>
                                                    </template>
                                                </div>
                                                <div>
                                                    <h3 class="font-heading font-bold text-base text-white leading-tight" x-text="exp.company"></h3>
                                                    <div class="text-amber-400 text-xs font-semibold mt-0.5" x-text="exp.title"></div>
                                                    <div class="flex flex-wrap items-center gap-2 mt-1 text-[10px] text-slate-400">
                                                        <div class="flex items-center gap-1 text-slate-400">
                                                            <svg class="w-3 h-3 text-amber-500/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                            <span x-text="exp.type"></span>
                                                        </div>
                                                        <span>•</span>
                                                        <div class="flex items-center gap-1 text-slate-400">
                                                            <svg class="w-3 h-3 text-amber-500/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                            <span x-text="exp.location"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Date Pill Badge -->
                                            <div class="clay-card self-start inline-flex items-center gap-1.5 px-3 py-1 rounded-full border border-amber-400/30 shadow-[0_0_10px_rgba(212,175,55,0.15)]">
                                                <svg class="w-3.5 h-3.5 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="text-[10px] font-mono font-bold text-amber-300 tracking-wide" x-text="exp.start + ' - ' + exp.end"></span>
                                            </div>
                                        </div>

                                        <!-- Job Description -->
                                        <div class="my-3">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 shadow-[0_0_6px_rgba(251,191,36,0.8)]"></span>
                                                <h4 class="text-white font-bold text-[10px] uppercase tracking-widest">DESKRIPSI PEKERJAAN</h4>
                                            </div>
                                            <div class="prose prose-invert prose-xs max-w-none text-slate-300 leading-relaxed list-disc-custom" x-html="exp.desc"></div>
                                        </div>

                                        <!-- Documentation Photos -->
                                        <div x-show="exp.photos && exp.photos.length > 0" class="pt-3 border-t border-dashed border-slate-800/80">
                                            <div class="flex items-center justify-between gap-2 mb-2">
                                                <div class="flex items-center gap-2">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 shadow-[0_0_6px_rgba(251,191,36,0.8)]"></span>
                                                    <h4 class="text-white font-bold text-[10px] uppercase tracking-widest">DOKUMENTASI KEGIATAN</h4>
                                                </div>
                                                <div class="px-2 py-0.5 rounded-full bg-[#111622] border border-slate-800 text-[9px] font-bold text-slate-400">
                                                    <span x-text="exp.photos.length"></span> Foto
                                                </div>
                                            </div>
                                            <div class="flex gap-2.5 overflow-x-auto hide-scrollbar pb-1">
                                                <template x-for="(photo, pIdx) in exp.photos" :key="pIdx">
                                                    <div class="flex-shrink-0 relative group cursor-pointer" @click.stop="openModal(exp.photos, pIdx)">
                                                        <div class="w-32 h-20 rounded-xl overflow-hidden border border-slate-700/60 shadow-lg relative">
                                                            <img :src="photo" class="w-full h-full object-cover select-none pointer-events-none" alt="Dokumentasi">
                                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Right Main Content (Active Detail Card for Desktop Screen) -->
                    <div class="hidden lg:block w-full flex-grow min-w-0">
                        <!-- The Big Clay Detail Card -->
                        <div class="w-full h-full p-4 sm:p-6 md:p-8 lg:p-10 clay-card relative overflow-hidden">
                            
                            <!-- Work & Career Tech Graphic -->
                            <div class="absolute -top-6 -right-6 w-64 h-64 pointer-events-none opacity-20 z-0">
                                <svg viewBox="0 0 200 200" fill="none" class="w-full h-full text-amber-400">
                                    <circle cx="120" cy="80" r="70" stroke="currentColor" stroke-width="1" stroke-dasharray="6 6" />
                                    <circle cx="120" cy="80" r="50" stroke="currentColor" stroke-width="1.5" />
                                    <g transform="translate(95, 55) scale(2)" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="7" width="20" height="14" rx="3" fill="currentColor" fill-opacity="0.1" />
                                        <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" />
                                        <path d="M12 12v3" />
                                        <path d="M2 11h20" stroke-dasharray="2 2" />
                                    </g>
                                    <circle cx="70" cy="50" r="4" fill="currentColor" />
                                    <circle cx="170" cy="110" r="4" fill="currentColor" />
                                    <line x1="70" y1="50" x2="90" y2="65" stroke="currentColor" stroke-width="1" stroke-dasharray="3 3" />
                                    <line x1="150" y1="95" x2="170" y2="110" stroke="currentColor" stroke-width="1" stroke-dasharray="3 3" />
                                </svg>
                            </div>

                            <template x-for="(exp, index) in experiences" :key="index">
                                <div x-show="(activeExp ?? 0) === index"
                                     x-transition:enter="transition ease-out duration-500"
                                     x-transition:enter-start="opacity-0 translate-y-6"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     class="w-full relative z-10">
                                    
                                    <!-- Card Header (Logo, Company, Title, Meta, Date Pill) -->
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 sm:gap-6 pb-4 sm:pb-6 border-b border-dashed border-slate-800/80">
                                        <div class="flex items-center gap-3.5 sm:gap-5">
                                            <!-- Logo Box -->
                                            <div class="clay-card w-14 h-14 sm:w-16 sm:h-16 md:w-20 md:h-20 flex items-center justify-center flex-shrink-0 relative overflow-hidden border border-amber-400/40 shadow-[0_0_25px_rgba(212,175,55,0.2)]">
                                                <template x-if="exp.logo">
                                                    <img :src="exp.logo" :alt="exp.company"
                                                         class="w-full h-full object-cover scale-[1.12] select-none pointer-events-none rounded-xl sm:rounded-2xl"
                                                         oncontextmenu="return false;"
                                                         ondragstart="return false;"
                                                         draggable="false">
                                                </template>
                                                <template x-if="!exp.logo">
                                                    <span class="text-amber-400 font-heading font-black text-xl sm:text-2xl md:text-3xl select-none" x-text="exp.company.substring(0,1)"></span>
                                                </template>
                                            </div>
                                            
                                            <div>
                                                <h3 class="font-heading font-bold text-lg sm:text-2xl text-white leading-tight" x-text="exp.company"></h3>
                                                <div class="text-amber-400 text-xs sm:text-sm font-semibold mt-0.5" x-text="exp.title"></div>
                                                
                                                <div class="flex flex-wrap items-center gap-2 sm:gap-3 mt-1.5 sm:mt-2 text-[11px] sm:text-xs text-slate-400">
                                                    <div class="flex items-center gap-1 text-slate-400">
                                                        <svg class="w-3.5 h-3.5 text-amber-500/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                        <span x-text="exp.type"></span>
                                                    </div>
                                                    <span>•</span>
                                                    <div class="flex items-center gap-1 text-slate-400">
                                                        <svg class="w-3.5 h-3.5 text-amber-500/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                        <span x-text="exp.location"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Date Pill Badge -->
                                        <div class="clay-card flex-shrink-0 inline-flex items-center gap-2 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-full border border-amber-400/30 shadow-[0_0_15px_rgba(212,175,55,0.15)] self-start sm:self-auto">
                                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="text-[11px] sm:text-xs font-mono font-bold text-amber-300 tracking-wide" x-text="exp.start + ' - ' + exp.end"></span>
                                        </div>
                                    </div>
                                    
                                    <!-- Job Description Section -->
                                    <div class="my-4 sm:my-6">
                                        <div class="flex items-center gap-2.5 mb-3 sm:mb-4">
                                            <span class="w-2 h-2 rounded-full bg-amber-400 shadow-[0_0_8px_rgba(251,191,36,0.8)]"></span>
                                            <h4 class="text-white font-bold text-[11px] sm:text-xs uppercase tracking-widest">DESKRIPSI PEKERJAAN</h4>
                                        </div>
                                        <div class="prose prose-invert prose-xs sm:prose-sm max-w-none text-slate-300 leading-relaxed list-disc-custom" x-html="exp.desc">
                                        </div>
                                    </div>
                                    
                                    <!-- Documentation Photos Section -->
                                    <div x-show="exp.photos && exp.photos.length > 0" class="pt-4 sm:pt-6 border-t border-dashed border-slate-800/80">
                                        <div class="flex items-center justify-between gap-3 mb-3 sm:mb-4">
                                            <div class="flex items-center gap-2.5">
                                                <span class="w-2 h-2 rounded-full bg-amber-400 shadow-[0_0_8px_rgba(251,191,36,0.8)]"></span>
                                                <h4 class="text-white font-bold text-[11px] sm:text-xs uppercase tracking-widest">DOKUMENTASI KEGIATAN</h4>
                                            </div>
                                            <div class="px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full bg-[#111622] border border-slate-800 text-[10px] font-bold text-slate-400">
                                                <span x-text="exp.photos.length"></span> Foto
                                            </div>
                                        </div>
                                        <div class="flex gap-3 sm:gap-4 overflow-x-auto hide-scrollbar pb-2">
                                            <template x-for="(photo, index) in exp.photos" :key="index">
                                                <div class="flex-shrink-0 relative group cursor-pointer" @click="openModal(exp.photos, index)">
                                                    <div class="w-36 h-24 sm:w-44 sm:h-28 rounded-2xl overflow-hidden border border-slate-700/60 shadow-lg relative">
                                                        <img :src="photo" 
                                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110 select-none pointer-events-none" 
                                                             alt="Dokumentasi"
                                                             oncontextmenu="return false;"
                                                             ondragstart="return false;"
                                                             draggable="false">
                                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Bottom Quote Clay Card (Positioned below the active detail card) -->
                <div class="mt-6 sm:mt-8 max-w-2xl sm:max-w-3xl mx-auto p-3.5 sm:p-4 md:p-5 clay-card flex items-center justify-center gap-3 sm:gap-4 text-center">
                    <div class="w-7 h-7 sm:w-9 sm:h-9 clay-circle flex items-center justify-center text-accentlight font-serif text-lg sm:text-2xl font-bold flex-shrink-0">
                        “
                    </div>
                    <p class="text-slate-300 text-xs sm:text-sm italic font-sans leading-relaxed">
                        Setiap pengalaman adalah pelajaran, setiap proyek adalah kesempatan untuk berkembang.
                    </p>
                </div>
            </div>


            
            <div x-show="experiences.length === 0" class="text-center py-12">
                <p class="text-slate-500 font-mono text-sm">Belum ada pengalaman yang ditambahkan.</p>
            </div>

            <!-- Image Modal (Lightbox) -->
            <template x-teleport="body">
                <div x-show="modalOpen" 
                     class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/95 p-4 backdrop-blur-md"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 style="display: none;"
                 @keydown.escape.window="modalOpen = false"
                 @keydown.right.window="nextImage()"
                 @keydown.left.window="prevImage()">
                 
                <!-- Close Button -->
                <button @click="modalOpen = false" class="absolute top-6 right-6 text-slate-400 hover:text-white transition-colors bg-[#111622] rounded-full p-2 border border-slate-700/50 z-[100000]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                
                <!-- Prev Button -->
                <button x-show="modalIndex > 0" @click.stop="prevImage()" class="absolute left-4 md:left-10 text-slate-400 hover:text-white transition-colors bg-[#111622]/80 backdrop-blur-sm rounded-full p-3 border border-slate-700/50 z-[100000]">
                    <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                
                <!-- Next Button -->
                <button x-show="modalIndex < modalImages.length - 1" @click.stop="nextImage()" class="absolute right-4 md:right-10 text-slate-400 hover:text-white transition-colors bg-[#111622]/80 backdrop-blur-sm rounded-full p-3 border border-slate-700/50 z-[100000]">
                    <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
                
                <!-- Image Container -->
                <div class="relative max-w-5xl max-h-[90vh] flex flex-col items-center justify-center z-[99999]" @click.away="modalOpen = false">
                    <img :src="modalImages[modalIndex]" 
                         class="max-w-full max-h-[85vh] rounded-xl object-contain shadow-2xl transition-all duration-300 select-none pointer-events-none"
                         oncontextmenu="return false;"
                         ondragstart="return false;"
                         draggable="false">
                    
                    <!-- Counter -->
                    <div class="absolute -bottom-10 left-1/2 -translate-x-1/2 text-slate-400 text-sm font-sans tracking-widest bg-black/50 px-4 py-1.5 rounded-full backdrop-blur-md border border-slate-800/80">
                        <span x-text="modalIndex + 1"></span> / <span x-text="modalImages.length"></span>
                    </div>
                </div>
            </div>
            </template>
        </div>

        <style>
            .list-disc-custom ul {
                list-style-type: none;
                padding-left: 0;
            }
            .list-disc-custom ul li {
                position: relative;
                padding-left: 1.5rem;
                margin-bottom: 0.75rem;
            }
            .list-disc-custom ul li::before {
                content: '';
                position: absolute;
                left: 0;
                top: 0.6rem;
                width: 0.375rem;
                height: 0.375rem;
                border-radius: 50%;
                background-color: #f59e0b;
                box-shadow: 0 0 8px rgba(245,158,11,0.6);
            }
            .hide-scrollbar::-webkit-scrollbar {
                display: none;
            }
            .hide-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>
    </section>

    <!-- SECTION: PROYEK -->
    <section id="proyek" class="px-4 sm:px-6 md:px-12 py-8 md:py-10 relative bg-darker futuristic-grid before:absolute before:inset-0 before:bg-darker before:z-[-1] overflow-hidden">
        
        <!-- Ambient Aurora Glow (10% Glow) -->
        <div class="absolute top-1/3 right-1/4 w-[500px] h-[500px] bg-amber-500/5 rounded-full blur-[140px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto relative z-10">
            <!-- Hero Header Showcase Area (2 Columns) -->
            <div class="flex flex-col lg:flex-row items-center justify-between gap-6 mb-4 md:mb-6">
                <!-- Left Column: Title & Subtitle (Centered on Mobile, Left-aligned on Desktop) -->
                <div class="w-full lg:w-1/2 flex flex-col items-center lg:items-start text-center lg:text-left">                  
                    <h2 class="font-heading text-3xl sm:text-4xl md:text-5xl text-white font-bold tracking-tight mb-3 sm:mb-4">
                        Karya &amp; <span class="text-accentlight neon-text-gold">Portofolio.</span>
                    </h2>
                    
                    <p class="text-slate-400 font-sans text-xs md:text-sm max-w-md leading-relaxed mb-0 sm:mb-6">
                        Mengubah ide menjadi solusi digital yang fungsional, estetis, dan berdampak nyata.
                    </p>
                </div>

                <!-- Right Column: Compact 3D Showcase Podium Component -->
                <div class="w-full lg:w-1/2 flex items-center justify-center relative min-h-[140px] sm:min-h-[200px] pointer-events-none select-none my-2 lg:my-0">
                    
                    <!-- Ambient Radial Gold Light -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-0">
                        <div class="w-40 h-40 sm:w-48 sm:h-48 bg-amber-500/10 rounded-full blur-[80px]"></div>
                    </div>

                    <!-- Outer Orbit Ring 1 (Large) -->
                    <div class="absolute w-[200px] h-[70px] sm:w-[300px] sm:h-[100px] rounded-[50%] border border-amber-400/25 pointer-events-none z-0" style="transform: rotate(-16deg); box-shadow: 0 0 25px rgba(212,175,55,0.08);">
                        <div class="absolute top-2 left-1/4 w-2 h-2 rounded-full bg-amber-400 shadow-[0_0_8px_#f59e0b]"></div>
                    </div>
                    <!-- Outer Orbit Ring 2 (Dashed) -->
                    <div class="absolute w-[230px] h-[85px] sm:w-[330px] sm:h-[115px] rounded-[50%] border border-dashed border-slate-700/60 pointer-events-none z-0" style="transform: rotate(-16deg);"></div>

                    <!-- 3D Scene Wrapper with Perspective -->
                    <div class="relative z-10 flex flex-col items-center justify-center w-full py-1 [perspective:1000px]">

                        <!-- 3D Floating Clay Tile (Tilted Sideways in True 3D Perspective) -->
                        <div class="relative z-20 w-32 h-32 sm:w-44 sm:h-44 rounded-[20px] sm:rounded-[26px] bg-gradient-to-br from-[#232d44] via-[#141b2b] to-[#0a0d15] border-2 border-amber-400/50 p-3 sm:p-3.5 shadow-[-12px_16px_30px_rgba(0,0,0,0.9),inset_2px_2px_8px_rgba(255,255,255,0.2),inset_-3px_-3px_10px_rgba(0,0,0,0.7),0_0_25px_rgba(212,175,55,0.2)] hover:scale-105 transition-all duration-700 ease-out flex flex-col justify-between mb-[-16px] sm:mb-[-20px] pointer-events-auto"
                             style="transform: rotateX(20deg) rotateY(-22deg) rotateZ(6deg); transform-style: preserve-3d;">
                            
                            <!-- Tile Header: Gold Code Icon + Top Right Clay Switch Widget -->
                            <div class="flex items-center justify-between">
                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-gradient-to-br from-amber-400/20 to-amber-500/10 border border-amber-400/30 flex items-center justify-center text-amber-400 font-mono font-black text-xs sm:text-base shadow-[inset_0_1px_1px_rgba(255,255,255,0.2),0_4px_12px_rgba(0,0,0,0.4)]">
                                    &lt;/&gt;
                                </div>
                                
                                <div class="px-1.5 py-0.5 sm:px-2 rounded-lg bg-[#090c14] dark:bg-[#090c14] border border-slate-800 dark:border-slate-800 shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)] flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span class="text-[7px] sm:text-[8px] font-mono font-bold text-slate-300 uppercase tracking-widest">DEV</span>
                                </div>
                            </div>

                            <!-- Tile Center/Bottom: Mini Dashboard Analytics Widget Card -->
                            <div class="p-1.5 sm:p-2 rounded-xl bg-[#080b12]/90 border border-amber-400/20 shadow-[inset_0_1px_2px_rgba(255,255,255,0.05),0_6px_15px_rgba(0,0,0,0.6)]">
                                <div class="flex items-center justify-between mb-0.5">
                                    <span class="text-[7px] sm:text-[8px] font-mono text-amber-400 font-semibold uppercase tracking-wider">ANALYTICS</span>
                                    <span class="text-[7px] sm:text-[8px] font-mono text-emerald-400 font-bold">+28.5%</span>
                                </div>
                                
                                <!-- Mini SVG Trendline Chart -->
                                <div class="w-full h-5 sm:h-6 mb-0.5">
                                    <svg viewBox="0 0 100 30" fill="none" class="w-full h-full">
                                        <defs>
                                            <linearGradient id="chartGradSm2" x1="0" y1="0" x2="0" y2="1">
                                                <stop offset="0%" stop-color="#f59e0b" stop-opacity="0.3"/>
                                                <stop offset="100%" stop-color="#f59e0b" stop-opacity="0"/>
                                            </linearGradient>
                                        </defs>
                                        <path d="M0 25 Q 20 20, 35 15 T 65 10 T 100 3 L 100 30 L 0 30 Z" fill="url(#chartGradSm2)" />
                                        <path d="M0 25 Q 20 20, 35 15 T 65 10 T 100 3" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" />
                                        <circle cx="100" cy="3" r="2.5" fill="#fbbf24" class="animate-ping" />
                                        <circle cx="100" cy="3" r="2" fill="#f59e0b" />
                                    </svg>
                                </div>

                                <!-- Stack Dots -->
                                <div class="flex items-center justify-between text-[6px] sm:text-[7px] font-mono text-slate-400 pt-0.5 border-t border-slate-800/80">
                                    <span class="flex items-center gap-0.5"><span class="w-1 h-1 rounded-full bg-amber-400"></span> Build</span>
                                    <span class="flex items-center gap-0.5"><span class="w-1 h-1 rounded-full bg-blue-400"></span> Deploy</span>
                                    <span class="flex items-center gap-0.5"><span class="w-1 h-1 rounded-full bg-emerald-400"></span> Live</span>
                                </div>
                            </div>

                        </div>

                        <!-- 3D Cylinder Podium Base (Bottom Stage) -->
                        <div class="relative z-10 w-36 sm:w-52 h-7 sm:h-9 rounded-[50%] bg-gradient-to-b from-[#1a2336] via-[#101625] to-[#070a12] border-t-2 border-amber-400/50 shadow-[0_10px_20px_rgba(0,0,0,0.95),0_0_20px_rgba(212,175,55,0.25)] flex items-center justify-center">
                            <!-- Bottom Rim Glow Ring -->
                            <div class="absolute -bottom-1.5 inset-x-3 h-3 sm:h-4 bg-amber-400/30 rounded-[50%] blur-md pointer-events-none"></div>
                        </div>

                    </div>

                    <!-- Decorative Floating 3D Spheres (Amber & Gold) -->
                    <div class="absolute top-2 left-6 w-3 sm:w-4 h-3 sm:h-4 rounded-full bg-gradient-to-br from-amber-200 via-amber-400 to-amber-600 shadow-[0_0_10px_rgba(212,175,55,0.7)] animate-bounce" style="animation-duration: 4.5s;"></div>
                    <div class="absolute bottom-4 right-4 w-4 sm:w-5 h-4 sm:h-5 rounded-full bg-gradient-to-br from-amber-400 via-amber-500 to-amber-700 border border-amber-300 shadow-xl"></div>
                    <div class="absolute top-1/3 right-2 w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full bg-gradient-to-br from-amber-300 to-amber-500 shadow-[0_0_6px_rgba(251,191,36,0.6)] animate-pulse"></div>
                </div>
            </div>

            <!-- Mobile Swipe Hint -->
            <div class="flex items-center justify-center gap-2 text-amber-400/70 text-xs font-mono mb-4 md:hidden">
                <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                </svg>
                <span>Geser ke samping untuk lihat proyek &rarr;</span>
            </div>

            <!-- Project Cards Grid / Horizontal Slider on Mobile -->
            <div class="flex md:grid md:grid-cols-2 gap-4 sm:gap-6 md:gap-8 overflow-x-auto snap-x snap-mandatory hide-scrollbar pb-4 -mx-4 px-4 sm:-mx-6 sm:px-6 md:mx-0 md:px-0">
                @foreach($projects as $project)
                <a href="{{ route('project.show', $project->slug) }}"
                   class="group relative clay-card p-4 sm:p-6 md:p-8 rounded-2xl bg-[#0a0d18]/70 border border-amber-400/30 hover:border-amber-400/70 transition-all duration-500 hover:-translate-y-2.5 hover:shadow-[0_0_30px_rgba(212,175,55,0.25)] cursor-pointer flex flex-col justify-between overflow-hidden relative z-10 backdrop-blur-md flex-shrink-0 w-[85vw] sm:w-[380px] md:w-auto snap-center md:flex-shrink">
                    
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
                    <div class="absolute -top-16 -right-16 w-56 h-56 bg-amber-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-amber-500/30 transition-all duration-700 z-0"></div>
                    <div class="absolute -bottom-16 -left-16 w-56 h-56 bg-amber-500/15 rounded-full blur-3xl pointer-events-none group-hover:bg-amber-500/25 transition-all duration-700 z-0"></div>
                    
                    <!-- Futuristic Grid Pattern & Code Circuit Watermark Overlay -->
                    <div class="absolute inset-0 bg-[radial-gradient(#d4af37_1px,transparent_1px)] [background-size:24px_24px] opacity-[0.05] group-hover:opacity-[0.1] transition-opacity duration-500 pointer-events-none z-0"></div>
                    
                    <!-- Decorative Top-Right Tech Circuit Lines -->
                    <div class="absolute top-0 right-0 w-36 h-36 opacity-15 group-hover:opacity-30 transition-opacity pointer-events-none z-0">
                        <svg viewBox="0 0 100 100" fill="none" class="w-full h-full text-amber-400">
                            <path d="M100 0H60L40 20H0" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                            <circle cx="60" cy="0" r="3" fill="currentColor"/>
                            <circle cx="40" cy="20" r="3" fill="currentColor"/>
                        </svg>
                    </div>

                    <div class="relative z-10">
                        <!-- Top Bar: Category Tag (Clay Card Pill matching Hero) -->
                        <div class="flex items-center justify-between gap-4 mb-4 sm:mb-6">
                            <span class="clay-card inline-flex items-center gap-1.5 px-3 py-1 sm:px-3.5 sm:py-1.5 rounded-full border border-amber-400/35 text-amber-300 font-mono text-[9px] sm:text-[10px] font-bold uppercase tracking-widest shadow-[0_0_12px_rgba(212,175,55,0.15)]">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                {{ $project->category }}
                            </span>
                        </div>

                        <!-- Card Body: Left Title, Right Image Frame -->
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 sm:gap-6 items-center mb-4 sm:mb-6">
                            <div class="sm:col-span-6 flex flex-col justify-center">
                                <h3 class="font-heading font-bold text-lg sm:text-2xl text-white group-hover:text-amber-300 transition-colors leading-tight tracking-tight">
                                    {{ $project->title }}
                                </h3>
                            </div>

                            <div class="sm:col-span-6">
                                <div class="clay-card w-full h-36 sm:h-40 rounded-2xl overflow-hidden border border-amber-400/30 shadow-[0_10px_30px_rgba(0,0,0,0.8),inset_0_1px_3px_rgba(255,255,255,0.1)] relative group-hover:scale-[1.03] group-hover:border-amber-400/60 transition-all duration-500 bg-[#070912]">
                                    @if($project->image)
                                        <img src="{{ Storage::disk('public')->url($project->image) }}" alt="{{ $project->title }}"
                                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 select-none pointer-events-none" loading="lazy"
                                             oncontextmenu="return false;"
                                             ondragstart="return false;"
                                             draggable="false">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-600 font-mono text-xs">
                                            [ No Image ]
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-[#040610]/80 via-transparent to-transparent opacity-40 group-hover:opacity-20 transition-opacity"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Bar: Tech Stacks & LIHAT DETAIL CTA -->
                    <div class="pt-3.5 sm:pt-4 border-t border-slate-800/80 flex flex-wrap sm:flex-nowrap items-center justify-between gap-3 sm:gap-4 relative z-10">
                        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                            @php
                                $stacks = $project->tech_stack ? array_map('trim', explode(',', $project->tech_stack)) : [];
                            @endphp
                            @foreach($stacks as $stack)
                                <span class="clay-card px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full border border-amber-400/20 text-[10px] sm:text-[11px] font-mono text-slate-300 inline-flex items-center gap-1 sm:gap-1.5 shadow-sm group-hover:border-amber-400/40 transition-colors">
                                    <span class="w-1 h-1 sm:w-1.5 sm:h-1.5 rounded-full bg-amber-400/80"></span>
                                    {{ $stack }}
                                </span>
                            @endforeach
                        </div>
                        <span class="text-[11px] sm:text-xs font-mono font-bold text-amber-300 group-hover:text-amber-400 flex items-center gap-1.5 transition-colors flex-shrink-0 neon-text-gold">
                            <span>LIHAT DETAIL</span>
                            <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </span>
                    </div>
                </a>
                @endforeach
            </div>

            <!-- Bottom CTA Button Below Projects Cards Grid -->
            <div class="mt-8 sm:mt-12 text-center">
                <a href="{{ route('projects.index') }}" class="group clay-btn bg-accent hover:bg-accentlight text-slate-950 font-sans font-bold uppercase tracking-wider text-xs px-6 py-3.5 cursor-pointer overflow-hidden inline-flex items-center gap-2">
                    <span>Lihat Semua Proyek</span>
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>

        </div>
    </section>

    <!-- SECTION: SERTIFIKAT -->
    @php
        $certsArray = $certificates->map(function($cert) {
            $certUrl = $cert->credential_url ?? $cert->url ?? null;
            $certImg = $cert->image ? Storage::disk('public')->url($cert->image) : null;
            return [
                'id' => $cert->id,
                'title' => $cert->title,
                'issuer' => $cert->issuer ?? null,
                'category' => $cert->certificateCategory?->name ?? $cert->category ?? null,
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

    <section id="sertifikat" class="px-6 md:px-12 py-8 md:py-10 relative scroll-mt-24 bg-darker overflow-hidden"
             x-data="{
                allCerts: {{ json_encode($certsArray) }},
                totalCertsCount: {{ $totalCertificates ?? \App\Models\Certificate::count() }},
                activeTab: 'all',
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
                    if (this.activeTab !== 'all') {
                        let cat = (c.issuer || c.title || '').toLowerCase();
                        let tab = this.activeTab.toLowerCase();
                        if (tab === 'sertifikat' && !cat.includes('certif') && !cat.includes('sertifikat') && !cat.includes('developer') && !cat.includes('llc') && !cat.includes('google') && !cat.includes('aws')) return false;
                        if (tab === 'kompetisi' && !cat.includes('kompetisi') && !cat.includes('competition') && !cat.includes('juara') && !cat.includes('hackathon')) return false;
                        if (tab === 'training' && !cat.includes('training') && !cat.includes('bootcamp') && !cat.includes('course') && !cat.includes('webinar')) return false;
                        if (tab === 'honors' && !cat.includes('honor') && !cat.includes('appreciation') && !cat.includes('award')) return false;
                    }
                    if (this.search.trim() !== '') {
                        let q = this.search.toLowerCase();
                        let matchTitle = (c.title || '').toLowerCase().includes(q);
                        let matchIssuer = (c.issuer || '').toLowerCase().includes(q);
                        let matchId = (c.credential_id || '').toLowerCase().includes(q);
                        if (!matchTitle && !matchIssuer && !matchId) return false;
                    }
                    return true;
                },
                get filteredList() {
                    let res = this.allCerts.filter(c => this.filterCert(c));
                    if (this.sort === 'oldest') {
                        return res.sort((a, b) => new Date(a.issue_date || '1970-01-01') - new Date(b.issue_date || '1970-01-01')).slice(0, 3);
                    }
                    return res.sort((a, b) => (new Date(b.issue_date || '1970-01-01') - new Date(a.issue_date || '1970-01-01')) || (b.id - a.id)).slice(0, 3);
                }
             }">

        <!-- Background Glow Orbs & Light Beams (Gold & Purple Radial Ambiance) -->
        <div class="absolute top-1/4 right-10 w-[600px] h-[600px] bg-amber-500/10 rounded-full blur-[150px] pointer-events-none"></div>
        <div class="absolute bottom-10 left-10 w-[450px] h-[450px] bg-amber-500/10 rounded-full blur-[130px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto relative z-10">
            
            <!-- ═══════════════════════════════════════
                 HERO HEADER SHOWCASE (Matching Project Section Style - Open 2 Columns)
            ═══════════════════════════════════════ -->
            <div class="flex flex-col lg:flex-row items-center justify-between gap-6 mb-4 md:mb-6 relative z-10">
                
                <!-- Left Column: Title & Subtitle (Centered on Mobile, Left-aligned on Desktop) -->
                <div class="w-full lg:w-1/2 flex flex-col items-center lg:items-start text-center lg:text-left">
                    <h2 class="font-heading text-3xl sm:text-4xl md:text-5xl text-white font-bold tracking-tight mb-3 sm:mb-4">
                        Sertifikasi &amp; <span class="text-accentlight neon-text-gold">Penghargaan.</span>
                    </h2>

                    <p class="text-slate-400 font-sans text-xs md:text-sm max-w-md leading-relaxed mb-0">
                        Validasi keahlian teknis, pencapaian kompetisi, dan sertifikasi resmi terverifikasi.
                    </p>
                </div>

                <!-- Right Column: 3D Floating Clay Ribbon Medal Podium -->
                <div class="w-full lg:w-1/2 flex items-center justify-center relative min-h-[140px] sm:min-h-[200px] pointer-events-none select-none my-2 lg:my-0">
                    
                    <!-- Ambient Radial Glow & Vertical Gold Rays -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-0">
                        <div class="w-48 h-48 bg-gradient-to-tr from-amber-500/20 via-amber-400/15 to-amber-300/20 rounded-full blur-[80px]"></div>
                        <div class="absolute inset-y-0 w-36 bg-gradient-to-b from-amber-400/15 via-amber-500/10 to-transparent blur-xl"></div>
                    </div>

                    <!-- Orbit Ring 1 (With Floating Orbs) -->
                    <div class="absolute w-[240px] h-[80px] sm:w-[300px] sm:h-[100px] rounded-[50%] border border-amber-400/30 pointer-events-none z-0" style="transform: rotate(-14deg); box-shadow: 0 0 25px rgba(212,175,55,0.15);">
                        <!-- Left Shiny Gold Floating Orb -->
                        <div class="absolute top-2 left-4 w-3 h-3 rounded-full bg-gradient-to-br from-amber-200 via-amber-400 to-amber-600 shadow-[0_0_12px_rgba(212,175,55,0.9)] animate-pulse"></div>
                        <!-- Right Purple Floating Orb -->
                        <div class="absolute bottom-1 right-4 w-4.5 h-4.5 rounded-full bg-gradient-to-br from-amber-300 via-amber-500 to-amber-700 shadow-[0_0_15px_rgba(212,175,55,0.8)]"></div>
                    </div>
                    
                    <!-- Orbit Ring 2 (Outer Glowing Trace) -->
                    <div class="absolute w-[270px] h-[95px] sm:w-[330px] sm:h-[115px] rounded-[50%] border border-dashed border-amber-400/25 pointer-events-none z-0" style="transform: rotate(-14deg);"></div>

                    <!-- 3D Pedestal & Floating Certificate Scene -->
                    <div class="relative z-10 flex flex-col items-center justify-center w-full py-1 [perspective:1000px]">

                        <!-- 3D Floating Clay Certificate Card (PRD Dark Clay & Gold Accent) -->
                        <div class="relative z-20 w-48 h-28 sm:w-56 sm:h-34 rounded-[22px] bg-gradient-to-br from-[#1c1f33] via-[#101324] to-[#070914] border-2 border-amber-400/50 p-4 sm:p-5 shadow-[-14px_18px_35px_rgba(0,0,0,0.95),inset_2px_2px_8px_rgba(255,255,255,0.15),inset_-3px_-3px_10px_rgba(0,0,0,0.8),0_0_30px_rgba(212,175,55,0.25)] hover:scale-105 transition-all duration-700 ease-out flex items-center justify-between mb-[-20px] pointer-events-auto"
                             style="transform: rotateX(20deg) rotateY(-18deg) rotateZ(5deg); transform-style: preserve-3d;">
                            
                            <!-- Left: 4 Embossed Skeleton Text Bars (PRD Claymorphism Lines) -->
                            <div class="space-y-2 w-1/2 pr-2">
                                <div class="w-full h-2 rounded-full bg-[#0a0c16] shadow-[inset_0_2px_4px_rgba(0,0,0,0.8),0_1px_1px_rgba(255,255,255,0.06)] border border-amber-400/15"></div>
                                <div class="w-[85%] h-2 rounded-full bg-[#0a0c16] shadow-[inset_0_2px_4px_rgba(0,0,0,0.8),0_1px_1px_rgba(255,255,255,0.06)] border border-amber-400/15"></div>
                                <div class="w-[65%] h-2 rounded-full bg-[#0a0c16] shadow-[inset_0_2px_4px_rgba(0,0,0,0.8),0_1px_1px_rgba(255,255,255,0.06)] border border-amber-400/15"></div>
                                <div class="w-[45%] h-2 rounded-full bg-[#0a0c16] shadow-[inset_0_2px_4px_rgba(0,0,0,0.8),0_1px_1px_rgba(255,255,255,0.06)] border border-amber-400/15"></div>
                            </div>

                            <!-- Right: 3D Gold Medal Badge with Ribbon Tails hanging below -->
                            <div class="relative flex flex-col items-center justify-center flex-shrink-0">
                                <!-- Round Medal Badge -->
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-gradient-to-tr from-amber-500 via-amber-300 to-amber-400 p-0.5 shadow-[0_0_20px_rgba(212,175,55,0.5),inset_0_2px_6px_rgba(255,255,255,0.4)] relative z-10 flex items-center justify-center">
                                    <div class="w-full h-full rounded-full bg-gradient-to-b from-[#1c192d] to-[#0c0919] flex items-center justify-center border border-amber-400/40">
                                        <!-- Gold Star Icon -->
                                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-amber-300 drop-shadow-[0_0_8px_rgba(212,175,55,0.9)]" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <!-- Hanging Ribbon Tails Below Medal -->
                                <div class="flex gap-1 -mt-2.5 z-0">
                                    <div class="w-3 h-5 bg-gradient-to-b from-amber-500 via-amber-700 to-amber-950 rounded-b-md shadow-md border-x border-b border-amber-400/40 transform -rotate-12" style="clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 80%, 0 100%);"></div>
                                    <div class="w-3 h-5 bg-gradient-to-b from-amber-500 via-amber-700 to-amber-950 rounded-b-md shadow-md border-x border-b border-amber-400/40 transform rotate-12" style="clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 80%, 0 100%);"></div>
                                </div>
                            </div>

                        </div>

                        <!-- Dual-Tiered 3D Cylinder Pedestal Stage Base -->
                        <div class="relative z-10 w-48 sm:w-56 flex flex-col items-center">
                            <!-- Top Pedestal Tier -->
                            <div class="w-full h-7 rounded-[50%] bg-gradient-to-b from-[#22263d] via-[#121528] to-[#070914] border-t-2 border-amber-400/50 shadow-[inset_0_2px_8px_rgba(255,255,255,0.1),0_8px_20px_rgba(0,0,0,0.9)] flex items-center justify-center"></div>
                            
                            <!-- Bottom Stage Ring with Glowing Gold Rim -->
                            <div class="w-[110%] h-6 -mt-3.5 rounded-[50%] bg-gradient-to-b from-[#181a2c] via-[#0d0f1e] to-[#04050a] border-b-2 border-amber-400 shadow-[0_0_25px_rgba(212,175,55,0.6),0_8px_20px_rgba(0,0,0,0.95)] relative">
                                <div class="absolute inset-x-5 -bottom-1 h-3 bg-amber-400/35 rounded-[50%] blur-md pointer-events-none"></div>
                                <div class="absolute inset-x-10 -bottom-1.5 h-2.5 bg-amber-500/20 rounded-[50%] blur-md pointer-events-none"></div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>



           

            <!-- Mobile Swipe Hint for Certificates -->
            <div class="flex items-center justify-center gap-2 text-amber-400/70 text-xs font-mono mb-4 sm:hidden">
                <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                </svg>
                <span>Geser ke samping untuk lihat sertifikat &rarr;</span>
            </div>

            <!-- CERTIFICATE CARDS GRID / Horizontal Slider on Mobile -->
            <div class="flex sm:grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 overflow-x-auto snap-x snap-mandatory hide-scrollbar pb-4 -mx-4 px-4 sm:mx-0 sm:px-0 mb-8 sm:mb-12">
                
                <template x-for="(cert, index) in filteredList" :key="cert.id || index">
                    <div class="group relative clay-card p-5 sm:p-6 rounded-2xl bg-[#0a0d18]/70 border border-amber-400/30 hover:border-amber-400/70 transition-all duration-500 hover:-translate-y-2.5 hover:shadow-[0_0_30px_rgba(212,175,55,0.25)] cursor-pointer flex flex-col justify-between overflow-hidden relative z-10 backdrop-blur-md flex-shrink-0 w-[85vw] sm:w-auto snap-center sm:flex-shrink"
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

            <template x-if="filteredList.length === 0">
                <div class="py-16 text-center bg-[#0e101e]/60 rounded-3xl border border-slate-800">
                    <p class="text-slate-500 font-mono text-sm">Tidak ada sertifikat yang ditemukan.</p>
                </div>
            </template>

            <!-- Bottom CTA Button Below Certificate Cards Grid -->
            <div class="mt-12 text-center">
                <a href="{{ route('certificates.index') }}" class="group clay-btn bg-accent hover:bg-accentlight text-slate-950 font-sans font-bold uppercase tracking-wider text-xs px-6 py-3.5 cursor-pointer overflow-hidden inline-flex items-center gap-2">
                    <span>Lihat Semua Sertifikat</span>
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>

        </div>

        <!-- Teleported Lightbox Modal -->
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
                            
                            <!-- LEFT COLUMN (Cols 1-7): Certificate Image / 3D Graphic Preview with Interactive Zoom & Protection -->
                            <div class="lg:col-span-7 w-full">
                                <div class="relative w-full aspect-[16/11] rounded-2xl overflow-hidden bg-slate-950 border border-amber-400/25 shadow-[inset_0_2px_10px_rgba(0,0,0,0.8),0_10px_30px_rgba(0,0,0,0.7)] flex items-center justify-center select-none"
                                     @contextmenu.prevent
                                     @dragstart.prevent
                                     @wheel.prevent="handleWheel($event)"
                                     @mousedown="startDrag($event)"
                                     @mousemove.window="onDrag($event)"
                                     @mouseup.window="stopDrag()"
                                     @mouseleave="stopDrag()"
                                     :class="zoomScale > 1 ? (isDragging ? 'cursor-grabbing' : 'cursor-grab') : 'cursor-zoom-in'"
                                     @dblclick="zoomScale === 1 ? (zoomScale = 2) : resetZoom()">
                                    
                                    <!-- Diagonal Security Watermark Layer -->
                                    <div class="absolute inset-0 z-20 pointer-events-none opacity-15 flex items-center justify-center rotate-[-25deg] select-none overflow-hidden">
                                        <span class="font-mono text-[11px] font-bold tracking-[0.35em] uppercase text-amber-300/80 whitespace-nowrap drop-shadow-md">
                                            {{ $user->name ?? 'REKA ANSORI MUBAROK' }} • VERIFIED CREDENTIAL • PREVIEW ONLY
                                        </span>
                                    </div>

                                    <!-- Protection Overlay -->
                                    <div class="absolute inset-0 z-10 bg-transparent" @contextmenu.prevent @dragstart.prevent></div>

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
                                                 class="max-w-full max-h-full object-contain rounded-lg pointer-events-none select-none"
                                                 oncontextmenu="return false;"
                                                 ondragstart="return false;">
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
                                        <div class="bg-[#070914]/80 border border-slate-800 p-3.5 rounded-xl space-y-2.5 font-mono text-xs">
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
                                           class="w-full py-3 px-5 rounded-full bg-gradient-to-r from-amber-400 via-amber-500 to-amber-400 text-slate-950 font-mono text-xs font-bold uppercase tracking-wider flex items-center justify-center gap-2 shadow-[0_0_20px_rgba(212,175,55,0.3)] hover:shadow-[0_0_30px_rgba(212,175,55,0.5)] transition-all">
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

    </section>

    <!-- SECTION: KONTAK (Footer) -->
    <footer id="kontak" class="px-4 sm:px-6 md:px-12 py-12 sm:py-16 relative bg-darker overflow-hidden">
        <!-- Ambient Glowing Orbs Background (Pure Gold & Dark Theme) -->
        <div class="absolute top-1/4 left-0 w-96 h-96 bg-amber-500/10 rounded-full blur-[140px] pointer-events-none"></div>
        <div class="absolute bottom-10 right-0 w-96 h-96 bg-amber-500/10 rounded-full blur-[140px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto relative z-10">

            <!-- Top Row Grid (Left Info & Right Form) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-14 items-start">

                <!-- LEFT COLUMN (Cols 1-6): Heading, Text, Clay Info Cards & Social Links -->
                <div class="lg:col-span-6 flex flex-col justify-between space-y-6 sm:space-y-8">
                    <div class="space-y-4 sm:space-y-6 text-center lg:text-left">
                        
                        <!-- Main Title (Gold Accent) -->
                        <h2 class="font-heading text-3xl sm:text-5xl lg:text-6xl font-bold text-white leading-[1.1] tracking-tight">
                            Mari Bekerja<br class="hidden sm:inline lg:inline">
                            <span class="text-accentlight neon-text-gold">Sama.</span><sup class="text-amber-400 text-2xl sm:text-4xl font-light font-sans ml-1">+</sup>
                        </h2>

                        <!-- Subdescription -->
                        <p class="text-slate-400 text-xs sm:text-sm leading-relaxed font-sans max-w-lg mx-auto lg:mx-0">
                            Saya selalu terbuka untuk peluang proyek, kolaborasi, atau sekadar ngobrol tentang ide menarik. Kirim pesan dan mari wujudkan sesuatu bersama.
                        </p>
                    </div>

                    <!-- 3 Clay Contact Cards (Availability, Response Time, Email) - Mobile Horizontal Swipe / Desktop Stack -->
                    <div class="w-full">
                        <!-- Mobile Swipe Hint for Contact Cards -->
                        <div class="flex items-center justify-center gap-2 text-amber-400/70 text-[11px] font-mono mb-2.5 lg:hidden">
                            <svg class="w-3.5 h-3.5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                            </svg>
                            <span>Geser info kontak &rarr;</span>
                        </div>

                        <div class="flex lg:grid lg:grid-cols-1 gap-3 overflow-x-auto snap-x snap-mandatory hide-scrollbar pb-3 lg:pb-0 -mx-4 px-4 sm:mx-0 sm:px-0">
                            
                            <!-- Card 1: Availability -->
                            <div class="group clay-card p-3.5 sm:p-4 rounded-2xl bg-[#090b1c]/90 border border-amber-400/20 hover:border-amber-400/50 transition-all duration-300 flex items-center justify-between shadow-[inset_1.5px_1.5px_5px_rgba(255,255,255,0.06),inset_-2px_-2px_6px_rgba(0,0,0,0.8),0_10px_25px_rgba(0,0,0,0.6)] hover:shadow-[0_0_20px_rgba(212,175,55,0.15)] relative flex-shrink-0 w-[82vw] sm:w-[320px] lg:w-full snap-center lg:flex-shrink">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-amber-400/10 border border-amber-400/30 flex items-center justify-center text-amber-400 shadow-[0_0_15px_rgba(212,175,55,0.2)] group-hover:scale-105 transition-transform flex-shrink-0">
                                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2H4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <span class="text-xs font-mono text-slate-400 block font-medium">Ketersediaan</span>
                                        <span class="text-xs sm:text-sm font-sans font-bold text-amber-300 block">Buka untuk Freelance</span>
                                        <span class="text-[11px] font-sans text-slate-400 block mt-0.5">Siap mengerjakan proyek baru</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2: Response Time -->
                            <div class="group clay-card p-3.5 sm:p-4 rounded-2xl bg-[#090b1c]/90 border border-amber-400/20 hover:border-amber-400/50 transition-all duration-300 flex items-center justify-between shadow-[inset_1.5px_1.5px_5px_rgba(255,255,255,0.06),inset_-2px_-2px_6px_rgba(0,0,0,0.8),0_10px_25px_rgba(0,0,0,0.6)] hover:shadow-[0_0_20px_rgba(212,175,55,0.15)] relative flex-shrink-0 w-[82vw] sm:w-[320px] lg:w-full snap-center lg:flex-shrink">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-amber-400/10 border border-amber-400/30 flex items-center justify-center text-amber-400 shadow-[0_0_15px_rgba(212,175,55,0.2)] group-hover:scale-105 transition-transform flex-shrink-0">
                                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div>
                                        <span class="text-xs font-mono text-slate-400 block font-medium">Waktu Respon</span>
                                        <span class="text-xs sm:text-sm font-sans font-bold text-amber-300 block">Dalam 24 Jam</span>
                                        <span class="text-[11px] font-sans text-slate-400 block mt-0.5">Saya akan membalas secepat mungkin</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 3: Email -->
                            <a href="mailto:{{ $user->email ?? 'rekaansori@example.com' }}"
                               class="group clay-card p-3.5 sm:p-4 rounded-2xl bg-[#090b1c]/90 border border-amber-400/20 hover:border-amber-400/50 transition-all duration-300 flex items-center justify-between shadow-[inset_1.5px_1.5px_5px_rgba(255,255,255,0.06),inset_-2px_-2px_6px_rgba(0,0,0,0.8),0_10px_25px_rgba(0,0,0,0.6)] hover:shadow-[0_0_20px_rgba(212,175,55,0.15)] relative cursor-pointer flex-shrink-0 w-[82vw] sm:w-[320px] lg:w-full snap-center lg:flex-shrink">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-amber-400/10 border border-amber-400/30 flex items-center justify-center text-amber-400 shadow-[0_0_15px_rgba(212,175,55,0.2)] group-hover:scale-105 transition-transform flex-shrink-0">
                                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <span class="text-xs font-mono text-slate-400 block font-medium">Email</span>
                                        <span class="text-xs sm:text-sm font-sans font-bold text-white group-hover:text-amber-300 transition-colors block truncate">{{ $user->email ?? 'rekaansori@example.com' }}</span>
                                        <span class="text-[11px] font-sans text-slate-400 block mt-0.5 truncate">Email terbaik untuk menghubungi saya</span>
                                    </div>
                                </div>
                            </a>

                        </div>
                    </div>

                    <!-- Connect With Me Social Buttons (Reusable Blade Component) -->
                    <div class="pt-2 flex flex-col items-center lg:items-start text-center lg:text-left">
                        <span class="text-xs font-mono uppercase tracking-widest text-slate-400 block mb-3 font-semibold">Terhubung Dengan Saya</span>
                        <x-social-links :links="$user->socialLinks ?? null" />
                    </div>
                </div>

                <!-- RIGHT COLUMN (Cols 7-12): 3D Claymorphism Contact Form Card (Gold Theme) -->
                <div class="lg:col-span-6 w-full">
                    <div class="relative clay-card rounded-2xl sm:rounded-[32px] bg-gradient-to-b from-[#10132a] via-[#090b1e] to-[#040612] border border-amber-400/25 p-5 sm:p-8 shadow-[inset_2px_2px_8px_rgba(255,255,255,0.08),inset_-4px_-4px_12px_rgba(0,0,0,0.9),0_25px_50px_rgba(0,0,0,0.8)] backdrop-blur-2xl">
                        
                        <!-- Form Header with Amber/Gold Icon & Orb -->
                        <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-800/80">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-amber-400/10 border border-amber-400/30 flex items-center justify-center text-amber-400 shadow-[0_0_15px_rgba(212,175,55,0.2)]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                </div>
                                <h3 class="font-heading font-bold text-lg sm:text-2xl text-white">Kirim Pesan</h3>
                            </div>
                            <!-- Glowing Orb Indicator -->
                            <div class="w-3.5 h-3.5 rounded-full bg-amber-400 shadow-[0_0_12px_rgba(212,175,55,0.9)] animate-pulse"></div>
                        </div>

                        <!-- Inline Alerts (Success / Error / Validation) -->
                        @if(session('contact_success'))
                            <div class="mb-5 p-3.5 sm:p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl flex items-center gap-3 text-emerald-200 text-xs font-mono shadow-[0_0_20px_rgba(16,185,129,0.2)]">
                                <div class="w-8 h-8 rounded-xl bg-emerald-500/20 border border-emerald-400/40 flex items-center justify-center text-emerald-400 flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div class="flex-1">
                                    <span class="font-bold block text-white text-sm">Pesan Berhasil Terkirim!</span>
                                    <span class="text-emerald-300 text-xs">{{ session('contact_success') }}</span>
                                </div>
                            </div>
                        @endif

                        @if(session('contact_error'))
                            <div class="mb-5 p-3.5 sm:p-4 bg-red-500/10 border border-red-500/30 rounded-2xl flex items-center gap-3 text-red-200 text-xs font-mono shadow-[0_0_20px_rgba(239,68,68,0.2)]">
                                <div class="w-8 h-8 rounded-xl bg-red-500/20 border border-red-400/40 flex items-center justify-center text-red-400 flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </div>
                                <div class="flex-1">
                                    <span class="font-bold block text-white text-sm">Gagal Mengirim Pesan!</span>
                                    <span class="text-red-300 text-xs">{{ session('contact_error') }}</span>
                                </div>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-5 p-3.5 sm:p-4 bg-red-900/30 border border-red-500/40 rounded-2xl text-xs font-mono text-red-300 shadow-[0_0_20px_rgba(239,68,68,0.15)]">
                                <div class="flex items-center gap-2 mb-2 font-bold text-red-200 text-sm">
                                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Pesan Gagal Terkirim — Harap Perbaiki:</span>
                                </div>
                                <ul class="list-disc pl-6 space-y-1 text-red-300/90">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="POST" class="space-y-3.5 sm:space-y-4">
                            @csrf
                            
                            <!-- Input Nama -->
                            <div>
                                <label for="name" class="block text-xs font-mono text-slate-300 mb-1.5 font-medium">Nama</label>
                                <div class="relative">
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                           placeholder="Masukkan nama Anda"
                                           class="w-full bg-[#060816] border border-amber-400/20 focus:border-amber-400 rounded-xl sm:rounded-2xl px-4 py-3 sm:py-3.5 pr-11 text-sm sm:text-xs font-sans text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-400/30 transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.7)]">
                                    <svg class="w-4 h-4 text-slate-500 absolute right-4 top-3.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                            </div>

                            <!-- Input Email -->
                            <div>
                                <label for="email" class="block text-xs font-mono text-slate-300 mb-1.5 font-medium">Email</label>
                                <div class="relative">
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                           placeholder="Masukkan email Anda"
                                           class="w-full bg-[#060816] border border-amber-400/20 focus:border-amber-400 rounded-xl sm:rounded-2xl px-4 py-3 sm:py-3.5 pr-11 text-sm sm:text-xs font-sans text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-400/30 transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.7)]">
                                    <svg class="w-4 h-4 text-slate-500 absolute right-4 top-3.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                            </div>

                            <!-- Input Subject -->
                            <div>
                                <label for="subject" class="block text-xs font-mono text-slate-300 mb-1.5 font-medium">Subject (opsional)</label>
                                <div class="relative">
                                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                                           placeholder="Topik pesan"
                                           class="w-full bg-[#060816] border border-amber-400/20 focus:border-amber-400 rounded-xl sm:rounded-2xl px-4 py-3 sm:py-3.5 pr-11 text-sm sm:text-xs font-sans text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-400/30 transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.7)]">
                                    <svg class="w-4 h-4 text-slate-500 absolute right-4 top-3.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                            </div>

                            <!-- Textarea Pesan -->
                            <div>
                                <label for="message" class="block text-xs font-mono text-slate-300 mb-1.5 font-medium">Pesan</label>
                                <div class="relative">
                                    <textarea id="message" name="message" rows="4" required
                                              placeholder="Tulis pesan Anda di sini..."
                                              class="w-full bg-[#060816] border border-amber-400/20 focus:border-amber-400 rounded-xl sm:rounded-2xl p-3.5 sm:p-4 pr-11 text-sm sm:text-xs font-sans text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-400/30 transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.7)] resize-none">{{ old('message') }}</textarea>
                                    <svg class="w-4 h-4 text-slate-500 absolute right-4 bottom-4 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </div>
                            </div>

                            <!-- Submit Button (Matches Download CV Button Clay Accent Style) -->
                            <button type="submit"
                                    class="group clay-btn bg-accent hover:bg-accentlight text-slate-950 font-sans font-bold text-xs tracking-wider uppercase w-full py-3.5 sm:py-4 px-6 rounded-xl sm:rounded-2xl flex items-center justify-center gap-3 cursor-pointer transition-all duration-300 active:scale-[0.99] mt-2">
                                <span>Kirim Pesan</span>
                                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform stroke-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            </button>

                            <!-- Security Note Below Button -->
                            <div class="flex items-center justify-center gap-2 pt-1.5 text-slate-400 font-sans text-xs text-center">
                                <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                <span>Pesan Anda aman dan tidak akan dibagikan ke pihak lain.</span>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <!-- Footer Copyright Bar -->
            <div class="mt-14 pt-8 border-t border-slate-800/80 flex flex-col sm:flex-row justify-between items-center gap-4 text-[11px] text-slate-500 uppercase tracking-[0.1em] font-mono">
                <p>&copy; {{ date('Y') }} {{ $user->name ?? 'Reka Ansori Mubarok' }}. All Rights Reserved.</p>
                <p>Built with Laravel & Tailwind CSS</p>
            </div>

        </div>
    </footer>


</body>
</html>
