<!DOCTYPE html>
<html lang="id" class="scroll-smooth bg-darker dark overflow-x-hidden">
<head>
    <x-theme-preference />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('components.navigation-prefetch')
    <title>{{ $project->title }} — {{ $user->name ?? 'Portfolio' }}</title>
    <meta name="description" content="{{ Str::limit($project->description, 160) }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('project.show', $project->slug) }}">
    <meta property="og:title" content="{{ $project->title }}">
    <meta property="og:description" content="{{ Str::limit($project->description, 160) }}">
    @if($project->image)
    <meta property="og:image" content="{{ Storage::disk('public')->url($project->image) }}">
    @endif

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/devicon.min.css">

    @vite('resources/css/app.css')

    <style>
        [x-cloak] { display: none !important; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #010010; }
        ::-webkit-scrollbar-thumb { background: #d4af37; border-radius: 999px; }

        /* Hero Detail Gradient Overlay — diagonal and bottom fade for seamless transition */
        .hero-overlay {
            background: 
                linear-gradient(to top, #010010 0%, transparent 40%),
                linear-gradient(45deg, #010010 0%, #010010 25%, rgba(1, 0, 16, 0.85) 50%, rgba(1, 0, 16, 0.3) 75%, transparent 100%);
        }

        /* Clay Detail Card */
        .clay-detail-card {
            background: rgba(14, 16, 30, 0.75);
            border: 1.5px solid rgba(212, 175, 55, 0.12);
            border-radius: 24px;
            backdrop-filter: blur(14px);
            box-shadow:
                inset -2px -2px 8px rgba(0, 0, 0, 0.5),
                inset 2px 2px 8px rgba(255, 255, 255, 0.04),
                0 8px 32px rgba(0, 0, 0, 0.6),
                0 0 0 1px rgba(255, 255, 255, 0.03);
        }

        /* Glow Divider Separator */
        .glow-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.3), transparent);
        }

        /* Tech Badge */
        .tech-badge {
            background: rgba(212, 175, 55, 0.08);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 12px;
            transition: all 0.25s ease;
        }
        .tech-badge:hover {
            background: rgba(212, 175, 55, 0.15);
            border-color: rgba(212, 175, 55, 0.4);
            box-shadow: 0 0 14px rgba(212, 175, 55, 0.15);
        }

        /* Feature Card */
        .feature-card {
            background: rgba(12, 14, 26, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 18px;
            backdrop-filter: blur(10px);
            box-shadow:
                inset -1px -1px 5px rgba(0, 0, 0, 0.4),
                inset 1px 1px 5px rgba(255, 255, 255, 0.03),
                0 4px 16px rgba(0, 0, 0, 0.4);
            transition: border-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
        }
        .feature-card:hover {
            border-color: rgba(212, 175, 55, 0.2);
            box-shadow:
                inset -1px -1px 5px rgba(0, 0, 0, 0.4),
                inset 1px 1px 5px rgba(255, 255, 255, 0.03),
                0 8px 24px rgba(0, 0, 0, 0.5),
                0 0 20px rgba(212, 175, 55, 0.08);
            transform: translateY(-3px);
        }

        /* Slide Transition */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.5s ease both; }

        /* Aurora pulse for hero */
        @keyframes aurora-pulse {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.05); }
        }
        .aurora-pulse { animation: aurora-pulse 8s ease-in-out infinite; }

        /* Dot indicator for slider */
        .dot-active { width: 24px; background: #d4af37; box-shadow: 0 0 8px rgba(212,175,55,0.6); }
        .dot-inactive { width: 8px; background: rgba(255,255,255,0.2); }

        /* Related card hover */
        .related-card {
            background: rgba(12, 14, 26, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 22px;
            overflow: hidden;
            transition: border-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        }
        .related-card:hover {
            border-color: rgba(212, 175, 55, 0.35);
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.6), 0 0 30px rgba(212,175,55,0.1);
        }
    </style>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
</head>
<body class="bg-darker text-white font-sans antialiased overflow-x-hidden selection:bg-amber-400/20 selection:text-amber-200 min-h-screen flex flex-col">

    <!-- Swarm Cursor Root -->

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
                    <li><a href="{{ route('projects.index') }}" class="nav-link-futuristic text-accentlight font-bold cursor-pointer">Proyek</a></li>
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

    <!-- ═══════════════════════════════════════
         HERO SECTION — Full Bleed Cover
    ═══════════════════════════════════════ -->
    <div class="relative w-full min-h-[75vh] md:min-h-[90vh] flex items-end pb-0 overflow-hidden futuristic-grid">

        <!-- Aurora Background Glow -->
        <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden">
            <div class="absolute top-1/4 left-1/4 w-[min(600px,80vw)] h-[min(400px,50vw)] bg-amber-500/5 rounded-full blur-[120px] aurora-pulse"></div>
            <div class="absolute top-1/3 right-1/4 w-[min(400px,60vw)] h-[min(300px,40vw)] bg-blue-500/4 rounded-full blur-[100px]"></div>
        </div>

        <!-- Cover Image -->
        <div class="absolute inset-0 z-0">
            @if($project->image)
                <img src="{{ Storage::disk('public')->url($project->image) }}"
                     alt="{{ $project->title }}"
                     class="w-full h-full object-cover opacity-50">
            @else
                <div class="w-full h-full bg-gradient-to-br from-slate-900 via-[#0a0d15] to-[#010010]"></div>
            @endif
            <!-- Hero overlay gradient -->
            <div class="hero-overlay absolute inset-0 z-1"></div>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 w-full max-w-7xl mx-auto px-6 md:px-12 pb-10 md:pb-14 pt-24 sm:pt-28">

            <!-- Back to Homepage Button -->
            <div class="mb-6">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-full bg-[#0a0d18]/90 border border-amber-400/30 hover:border-amber-400 text-amber-300 hover:text-amber-400 text-xs font-mono font-bold transition-all duration-300 shadow-[0_0_15px_rgba(212,175,55,0.1)] hover:shadow-[0_0_20px_rgba(212,175,55,0.3)] hover:-translate-x-1 backdrop-blur-md cursor-pointer group">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 lg:gap-8">

                <!-- Left: Title & Meta -->
                <div class="max-w-2xl fade-up">
                    <!-- Category Badge -->
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-amber-400/10 border border-amber-400/25 text-amber-300 font-mono text-[10px] uppercase tracking-widest mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                        {{ $project->category }}
                    </div>

                    <h1 class="font-heading text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-4 tracking-tight neon-text-gold break-words">
                        {{ $project->title }}
                    </h1>

                    <!-- CTA Buttons -->
                    <div class="flex flex-wrap items-center gap-3">
                        @if($project->project_url)
                        <a href="{{ $project->project_url }}" target="_blank" rel="noopener"
                           class="group clay-btn bg-accent hover:bg-accentlight text-slate-950 font-sans font-bold uppercase tracking-wider text-xs px-6 py-3.5 cursor-pointer overflow-hidden inline-flex items-center gap-2.5 shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            <span>LIVE PREVIEW</span>
                        </a>
                        @endif
                        @if($project->repo_url)
                        <a href="{{ $project->repo_url }}" target="_blank" rel="noopener"
                           class="group clay-btn bg-white/10 hover:bg-white/20 text-white font-sans font-bold uppercase tracking-wider text-xs px-6 py-3.5 cursor-pointer overflow-hidden inline-flex items-center gap-2.5 border border-white/20 shadow-lg">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/>
                            </svg>
                            <span>SOURCE CODE</span>
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Right: Stats Pills (Year, Duration, Role, Client) -->
                @if($project->year || $project->duration || $project->role || $project->client)
                <div class="clay-detail-card p-4 sm:p-5 flex flex-wrap gap-4 sm:gap-6 lg:flex-col lg:gap-4 w-full lg:min-w-[200px] lg:w-auto fade-up" style="animation-delay: 150ms;">
                    @if($project->year)
                    <div>
                        <span class="block text-[9px] font-mono uppercase tracking-[0.2em] text-slate-500 mb-1">Tahun</span>
                        <span class="text-white font-heading font-bold text-sm">{{ $project->year }}</span>
                    </div>
                    @endif
                    @if($project->duration)
                    <div>
                        <span class="block text-[9px] font-mono uppercase tracking-[0.2em] text-slate-500 mb-1">Durasi</span>
                        <span class="text-white font-heading font-bold text-sm">{{ $project->duration }}</span>
                    </div>
                    @endif
                    @if($project->role)
                    <div>
                        <span class="block text-[9px] font-mono uppercase tracking-[0.2em] text-slate-500 mb-1">Peran</span>
                        <span class="text-amber-300 font-heading font-semibold text-sm">{{ $project->role }}</span>
                    </div>
                    @endif
                    @if($project->client)
                    <div>
                        <span class="block text-[9px] font-mono uppercase tracking-[0.2em] text-slate-500 mb-1">Klien</span>
                        <span class="text-white font-heading font-bold text-sm">{{ $project->client }}</span>
                    </div>
                    @endif
                </div>
                @endif

            </div>
        </div>
    </div>



    <!-- ═══════════════════════════════════════
         MAIN CONTENT AREA
    ═══════════════════════════════════════ -->
    <div class="relative z-10 bg-darker flex-grow overflow-hidden">

        <!-- Aurora ambient glow for content area -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[min(800px,100vw)] h-[400px] bg-amber-500/4 rounded-full blur-[150px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 md:px-12 py-16 md:py-20 relative z-10">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 md:gap-14">

                <!-- ────────────────────────────────────
                     LEFT COLUMN: Overview + Features + Screenshots
                ──────────────────────────────────── -->
                <div class="lg:col-span-2 space-y-12">

                    <!-- Overview Section -->
                    <div class="fade-up">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-7 h-7 rounded-xl bg-amber-400/10 border border-amber-400/25 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <span class="text-[11px] font-mono uppercase tracking-widest text-amber-400 font-semibold">OVERVIEW</span>
                        </div>
                        <div class="clay-detail-card p-7">
                            <p class="text-slate-300 font-sans leading-relaxed text-[15px]">
                                {!! nl2br(e($project->description)) !!}
                            </p>

                            {{-- Feature Icons Row (4-col) --}}
                            @if($project->features && is_array($project->features) && count($project->features) > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-8">
                                @foreach(array_slice($project->features, 0, 4) as $feature)
                                <div class="feature-card p-4 flex flex-col items-center text-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-amber-400/10 border border-amber-400/20 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-white font-heading font-semibold text-xs">{{ $feature['title'] ?? $feature }}</p>
                                        @if(isset($feature['desc']))
                                        <p class="text-slate-500 font-sans text-[11px] mt-1 leading-relaxed">{{ $feature['desc'] }}</p>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Preview Screenshots Slider -->
                    @if($project->gallery && is_array($project->gallery) && count($project->gallery) > 0)
                    @php
                        $galleryUrls = array_values(array_map(
                            fn($img) => Storage::disk('public')->url($img),
                            $project->gallery
                        ));
                    @endphp
                    <div class="fade-up" style="animation-delay: 100ms;"
                         x-data="{
                            slides: {{ json_encode($galleryUrls) }},
                            current: 0,
                            lightbox: false,
                            lightboxIndex: 0,
                            prev() { this.current = (this.current - 1 + this.slides.length) % this.slides.length; },
                            next() { this.current = (this.current + 1) % this.slides.length; },
                            openLightbox(i) { this.lightboxIndex = i; this.lightbox = true; },
                            prevLb() { this.lightboxIndex = (this.lightboxIndex - 1 + this.slides.length) % this.slides.length; },
                            nextLb() { this.lightboxIndex = (this.lightboxIndex + 1) % this.slides.length; }
                         }"
                         @keydown.arrow-left.window="lightbox ? prevLb() : prev()"
                         @keydown.arrow-right.window="lightbox ? nextLb() : next()"
                         @keydown.escape.window="lightbox = false">

                        <!-- Section Header -->
                        <div class="flex items-center justify-between mb-5">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-xl bg-amber-400/10 border border-amber-400/25 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span class="text-[11px] font-mono uppercase tracking-widest text-amber-400 font-semibold">PREVIEW SCREENSHOTS</span>
                            </div>
                            <span class="font-mono text-xs text-slate-500" x-text="`${current + 1} / ${slides.length}`"></span>
                        </div>

                        <!-- Main Slider -->
                        <div class="clay-detail-card overflow-hidden cursor-zoom-in group relative aspect-video"
                             @click="openLightbox(current)">
                            @foreach($galleryUrls as $i => $url)
                            <div x-show="current === {{ $i }}"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="absolute inset-0">
                                <img src="{{ $url }}" alt="Screenshot {{ $i + 1 }}" class="w-full h-full object-cover" loading="lazy">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                            </div>
                            @endforeach

                            <!-- Fullscreen Hint -->
                            <div class="absolute bottom-4 right-4 bg-black/50 backdrop-blur-sm text-amber-300 text-[10px] font-mono px-3 py-1.5 rounded-lg flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none border border-amber-400/20">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                </svg>
                                Fullscreen
                            </div>

                            <!-- Nav Arrows -->
                            <button @click.stop="prev()" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 clay-circle flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer z-10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <button @click.stop="next()" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 clay-circle flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer z-10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Dot Indicators -->
                        <div class="flex justify-center gap-2 mt-4">
                            @foreach($galleryUrls as $i => $url)
                            <button @click="current = {{ $i }}"
                                    :class="current === {{ $i }} ? 'dot-active' : 'dot-inactive'"
                                    class="h-2 rounded-full transition-all duration-300 cursor-pointer"></button>
                            @endforeach
                        </div>

                        <!-- Thumbnail Strip -->
                        <div class="flex gap-3 mt-4 overflow-x-auto pb-2">
                            @foreach($galleryUrls as $i => $url)
                            <button @click="current = {{ $i }}"
                                    :class="current === {{ $i }} ? 'ring-2 ring-amber-400 opacity-100' : 'opacity-40 hover:opacity-70'"
                                    class="flex-shrink-0 w-20 h-14 rounded-xl overflow-hidden border border-white/10 transition-all duration-200 cursor-pointer">
                                <img src="{{ $url }}" alt="Thumb {{ $i + 1 }}" class="w-full h-full object-cover" loading="lazy">
                            </button>
                            @endforeach
                        </div>

                        <!-- LIGHTBOX -->
                        <div x-show="lightbox"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="fixed inset-0 z-[999] bg-black/95 backdrop-blur-lg flex items-center justify-center p-4"
                             @click.self="lightbox = false"
                             style="display:none;">
                            <button @click="lightbox = false" class="absolute top-5 right-5 w-10 h-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-amber-400/20 text-white border border-white/10 hover:border-amber-400/30 transition-all cursor-pointer z-10">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                            <div class="absolute top-5 left-1/2 -translate-x-1/2 font-mono text-xs text-white/40" x-text="`${lightboxIndex + 1} / ${slides.length}`"></div>
                            <div class="relative max-w-6xl w-full flex items-center gap-4">
                                <button @click="prevLb()" class="flex-shrink-0 w-11 h-11 rounded-full clay-circle flex items-center justify-center text-white cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>
                                <div class="flex-1 rounded-2xl overflow-hidden border border-white/10">
                                    @foreach($galleryUrls as $i => $url)
                                    <img src="{{ $url }}" alt="Screenshot {{ $i + 1 }}"
                                         x-show="lightboxIndex === {{ $i }}"
                                         class="w-full h-auto max-h-[80vh] object-contain" loading="lazy">
                                    @endforeach
                                </div>
                                <button @click="nextLb()" class="flex-shrink-0 w-11 h-11 rounded-full clay-circle flex items-center justify-center text-white cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

                <!-- ────────────────────────────────────
                     RIGHT COLUMN: Tech Stack + Details
                ──────────────────────────────────── -->
                <div class="lg:col-span-1 space-y-6 fade-up" style="animation-delay: 200ms;">

                    <!-- Tech Stack Card -->
                    @if($project->tech_stack)
                    @php
                        $colorMapping = [
                            'laravel'     => ['color' => 'rgba(239, 68, 68, 0.45)', 'class' => 'devicon-laravel-original colored'],
                            'livewire'    => ['color' => 'rgba(236, 72, 153, 0.45)', 'class' => 'devicon-livewire-plain colored'],
                            'tailwind'    => ['color' => 'rgba(6, 182, 212, 0.45)', 'class' => 'devicon-tailwindcss-original colored'],
                            'mysql'       => ['color' => 'rgba(59, 130, 246, 0.45)', 'class' => 'devicon-mysql-original colored'],
                            'alpine'      => ['color' => 'rgba(20, 184, 166, 0.45)', 'class' => 'devicon-alpinejs-plain colored'],
                            'chart'       => ['color' => 'rgba(217, 70, 239, 0.45)', 'class' => 'devicon-chartjs-plain colored'],
                            'php'         => ['color' => 'rgba(120, 124, 180, 0.45)', 'class' => 'devicon-php-original colored'],
                            'react'       => ['color' => 'rgba(56, 189, 248, 0.45)', 'class' => 'devicon-react-original colored'],
                            'vue'         => ['color' => 'rgba(74, 222, 128, 0.45)', 'class' => 'devicon-vuejs-original colored'],
                            'javascript'  => ['color' => 'rgba(234, 179, 8, 0.45)',  'class' => 'devicon-javascript-plain colored'],
                            'js'          => ['color' => 'rgba(234, 179, 8, 0.45)',  'class' => 'devicon-javascript-plain colored'],
                            'typescript'  => ['color' => 'rgba(59, 130, 246, 0.45)', 'class' => 'devicon-typescript-plain colored'],
                            'ts'          => ['color' => 'rgba(59, 130, 246, 0.45)', 'class' => 'devicon-typescript-plain colored'],
                            'html'        => ['color' => 'rgba(249, 115, 22, 0.45)',  'class' => 'devicon-html5-plain colored'],
                            'css'         => ['color' => 'rgba(59, 130, 246, 0.45)', 'class' => 'devicon-css3-plain colored'],
                            'bootstrap'   => ['color' => 'rgba(168, 85, 247, 0.45)', 'class' => 'devicon-bootstrap-plain colored'],
                            'git'         => ['color' => 'rgba(240, 80, 50, 0.45)',  'class' => 'devicon-git-plain colored'],
                            'github'      => ['color' => 'rgba(255, 255, 255, 0.45)', 'class' => 'devicon-github-original colored'],
                            'docker'      => ['color' => 'rgba(14, 165, 233, 0.45)', 'class' => 'devicon-docker-plain colored'],
                            'postgres'    => ['color' => 'rgba(51, 103, 145, 0.45)', 'class' => 'devicon-postgresql-original colored'],
                            'mongo'       => ['color' => 'rgba(71, 162, 72, 0.45)',  'class' => 'devicon-mongodb-plain colored'],
                            'python'      => ['color' => 'rgba(255, 224, 82, 0.45)',  'class' => 'devicon-python-plain colored'],
                            'vite'        => ['color' => 'rgba(189, 52, 254, 0.45)', 'class' => 'devicon-vitejs-plain colored'],
                            'next'        => ['color' => 'rgba(255, 255, 255, 0.45)', 'class' => 'devicon-nextjs-plain colored'],
                            'sass'        => ['color' => 'rgba(236, 72, 153, 0.45)', 'class' => 'devicon-sass-original colored'],
                            'redis'       => ['color' => 'rgba(220, 56, 43, 0.45)',  'class' => 'devicon-redis-plain colored'],
                            'firebase'    => ['color' => 'rgba(255, 202, 40, 0.45)',  'class' => 'devicon-firebase-plain colored'],
                        ];
                    @endphp
                    <div class="clay-detail-card p-6">
                        <div class="flex items-center gap-2.5 mb-6">
                            <span class="text-xs font-mono uppercase tracking-[0.2em] text-amber-400 font-bold">TECHNOLOGIES</span>
                        </div>
                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-x-4 gap-y-6">
                            @foreach(is_array($project->tech_stack) ? $project->tech_stack : [] as $tech)
                                @php
                                    $techTrimmed = trim($tech);
                                    $normalized = strtolower($techTrimmed);
                                    $matched = null;
                                    foreach ($colorMapping as $key => $data) {
                                        if (str_contains($normalized, $key)) {
                                            $matched = $data;
                                            break;
                                        }
                                    }
                                    if ($matched) {
                                        $iconClass = $matched['class'];
                                        $glowColor = $matched['color'];
                                    } else {
                                        // Dynamic slug mapping for Devicon
                                        $slug = preg_replace('/[^a-z0-9]/', '', $normalized);
                                        $iconClass = "devicon-" . $slug . "-plain colored";
                                        
                                        // Dynamic glow color matching the name hash
                                        $hash = md5($techTrimmed);
                                        $r = hexdec(substr($hash, 0, 2)) % 150 + 100;
                                        $g = hexdec(substr($hash, 2, 2)) % 150 + 100;
                                        $b = hexdec(substr($hash, 4, 2)) % 150 + 100;
                                        $glowColor = "rgba($r, $g, $b, 0.4)";
                                    }
                                @endphp
                                <div class="flex flex-col items-center gap-2.5 group cursor-default">
                                    <div class="w-14 h-14 rounded-2xl bg-[#080718]/85 flex items-center justify-center transition-all duration-300 transform group-hover:scale-105"
                                         style="border: 1px solid {{ str_replace('0.45', '0.2', $glowColor) }}; box-shadow: 0 0 15px {{ str_replace('0.45', '0.12', $glowColor) }};"
                                         onmouseover="this.style.borderColor='{{ $glowColor }}'; this.style.boxShadow='0 0 20px {{ $glowColor }}';"
                                         onmouseout="this.style.borderColor='{{ str_replace('0.45', '0.2', $glowColor) }}'; this.style.boxShadow='0 0 15px {{ str_replace('0.45', '0.12', $glowColor) }}';">
                                        <i class="{{ $iconClass }} text-3xl transition-transform duration-300 group-hover:scale-110"></i>
                                    </div>
                                    <span class="text-[11px] text-slate-400 font-sans tracking-wide text-center group-hover:text-white transition-colors duration-200">
                                        {{ $techTrimmed }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Related Projects Card (Karya Lainnya) -->
                    @if($related->isNotEmpty())
                    <div class="clay-detail-card p-6 sticky top-24">
                        <div class="flex items-center gap-2.5 mb-5">
                            <span class="text-xs font-mono uppercase tracking-[0.2em] text-amber-400 font-bold">Karya Lainnya</span>
                        </div>

                        <div class="space-y-4">
                            @foreach($related as $rel)
                            <a href="{{ route('project.show', $rel->slug) }}" class="flex items-center gap-4 p-3 rounded-xl hover:bg-white/5 border border-transparent hover:border-white/5 transition-all duration-300 group">
                                <div class="w-16 h-12 rounded-lg overflow-hidden flex-shrink-0 bg-slate-800">
                                    @if($rel->image)
                                        <img src="{{ Storage::disk('public')->url($rel->image) }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-slate-800 to-slate-950"></div>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-grow">
                                    <span class="block text-[9px] font-mono uppercase tracking-widest text-amber-400/80 mb-0.5">{{ $rel->category }}</span>
                                    <h4 class="text-slate-200 font-heading font-semibold text-xs leading-snug truncate group-hover:text-white transition-colors">
                                        {{ $rel->title }}
                                    </h4>
                                </div>
                                <div class="flex-shrink-0 text-slate-500 group-hover:text-amber-400 transition-colors">
                                    <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>
            </div>

        </div>



        <!-- ═══════════════════════════════════════
             QUOTE BANNER FOOTER
        ═══════════════════════════════════════ -->
        <div class="max-w-7xl mx-auto px-6 md:px-12 py-10">
            <div class="clay-detail-card p-6 flex items-center gap-5">
                <div class="w-10 h-10 rounded-xl bg-amber-400/10 border border-amber-400/20 flex items-center justify-center flex-shrink-0 text-amber-400 font-serif text-2xl font-bold">"</div>
                <p class="text-slate-400 text-sm italic font-sans leading-relaxed">
                    Setiap proyek adalah kesempatan untuk menciptakan sesuatu yang bermakna.
                </p>
            </div>
        </div>

        <!-- ═══════════════════════════════════════
             FOOTER
        ═══════════════════════════════════════ -->
        <footer class="py-10 px-6 md:px-12">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-slate-600 text-[10px] font-mono uppercase tracking-widest">
                    &copy; {{ date('Y') }} {{ $user->name ?? 'Portfolio' }}. All rights reserved.
                </p>
                <div class="flex items-center gap-5">
                    <a href="{{ route('projects.index') }}" class="text-slate-500 hover:text-amber-300 text-[10px] font-mono uppercase tracking-widest transition-colors cursor-pointer">
                        Semua Proyek
                    </a>
                    <span class="text-slate-800">·</span>
                    <a href="{{ route('home') }}" class="text-slate-500 hover:text-white text-[10px] font-mono uppercase tracking-widest transition-colors cursor-pointer">
                        Beranda
                    </a>
                </div>
            </div>
        </footer>

    </div>

</body>
</html>
