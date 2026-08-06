<style>
    /* ============================================
     * REBUILT CSS BERDASARKAN SCREENSHOT DESAIN
     * ============================================ */

    /* 1. SIDEBAR BACKGROUND */
    .fi-sidebar, 
    .fi-sidebar > header, 
    .fi-sidebar-nav {
        background-color: #f59e0b !important; /* Warna Amber */
        border-right: none !important;
    }
    
    .fi-sidebar .fi-logo,
    .fi-sidebar .fi-sidebar-item-label,
    .fi-sidebar .fi-icon,
    .fi-sidebar .fi-sidebar-group-label {
        color: #ffffff !important;
    }
    
    .fi-sidebar .fi-sidebar-group-label {
        opacity: 0.9 !important;
        font-weight: 700 !important;
    }

    /* 1b. SIDEBAR SPACING & ALIGNMENT FIX */
    .fi-sidebar-nav {
        padding-left: 1.1rem !important;
        padding-right: 0.25rem !important;
    }

    .fi-sidebar .fi-sidebar-group-items {
        gap: 0.15rem !important; /* Kurangi jarak vertikal antar menu */
    }

    /* Seragamkan padding & margin semua item (aktif maupun tidak) agar rata secara horizontal */
    .fi-sidebar .fi-sidebar-item-btn {
        border-radius: 9999px !important; /* Bentuk pill untuk semua (hover/aktif) */
        transition: all 0.2s ease !important;
    }
    
    /* Kurangi jarak vertikal antar GRUP menu (misal antara Dashboard dan Manajemen Konten) */
    .fi-sidebar .fi-sidebar-nav-groups {
        gap: 0.5rem !important;
    }

    /* 2. SIDEBAR ACTIVE ITEM (Pill Putih & Teks Oranye) */
    /* Target berdasarkan kombinasi class aktif Filament */
    .fi-sidebar .fi-sidebar-item-active > a,
    .fi-sidebar .fi-sidebar-item-active > button,
    .fi-sidebar .fi-sidebar-item.fi-active > a,
    .fi-sidebar-item-active .fi-sidebar-item-btn {
        background-color: #ffffff !important;
        /* border-radius is already applied globally above */
    }
    
    /* PAKSA warna teks dan ikon di dalam item aktif menjadi oranye */
    .fi-sidebar .fi-sidebar-item-active span,
    .fi-sidebar .fi-sidebar-item-active svg,
    .fi-sidebar .fi-sidebar-item.fi-active span,
    .fi-sidebar .fi-sidebar-item.fi-active svg,
    .fi-sidebar-item-active .fi-sidebar-item-label,
    .fi-sidebar-item-active .fi-icon {
        color: #f59e0b !important;
        fill: #f59e0b !important;
    }

    /* Efek hover untuk item sidebar yang tidak aktif (Transparan putih) */
    .fi-sidebar .fi-sidebar-item:not(.fi-sidebar-item-active) > a:hover,
    .fi-sidebar .fi-sidebar-item:not(.fi-sidebar-item-active) > button:hover {
        background-color: rgba(255, 255, 255, 0.15) !important;
    }

    /* 3. SIDEBAR COLLAPSE BUTTON (Tombol Panah di pinggir desktop) */
    .fi-sidebar-collapse-btn,
    .fi-sidebar-collapse-button {
        background-color: #ffffff !important;
        color: #f59e0b !important;
        border: 1px solid #e5e7eb !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
    }
    .fi-sidebar-collapse-btn svg,
    .fi-sidebar-collapse-button svg {
        color: #f59e0b !important;
    }

    /* 4. TOMBOL "Lihat CV" DI TOPBAR (Gaya Outline Putih-Oranye) */
    .cv-button {
        background-color: #ffffff !important;
        color: #f59e0b !important;
        border: 1px solid #f59e0b !important;
        border-radius: 9999px !important; /* Pill shape */
        padding: 0.375rem 1.25rem !important;
        transition: all 0.2s ease-in-out !important;
        font-weight: 600 !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;
    }
    .cv-button:hover {
        background-color: #f59e0b !important;
        color: #ffffff !important;
    }
    .cv-button svg {
        color: inherit !important;
    }
    
    /* 5. MENYEMBUNYIKAN TOMBOL CV & PROFIL DI SIMPLE LAYOUT (Login/Edit Profile) */
    .fi-simple-layout .cv-button-wrapper,
    .fi-simple-layout .fi-simple-layout-header {
        display: none !important;
    }

    /* 6. PENYESUAIAN DESAIN KARTU (Rounded) */
    .fi-ta-ctn, 
    .fi-wi-stats-overview-stat,
    .fi-wi-chart {
        border-radius: 1rem !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important;
    }

    /* 7. WIDGET STATISTIK KUSTOM */
    .stat-card {
        background-color: #ffffff;
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex: 1; 
        min-width: 220px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        transition: box-shadow 0.2s, transform 0.2s;
        border: 1px solid #f3f4f6;
    }
    .stat-card-label {
        color: #64748b;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .stat-card-value {
        font-size: 2.25rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    /* 8. TOPBAR BACKGROUND */
    .fi-topbar {
        background-color: #ffffff !important;
        border-bottom: 1px solid rgba(0,0,0,0.05) !important;
        position: relative !important;
        z-index: 100 !important;
    }

    /* 9. BADGE (Seperti angka 1 di Message) */
    .fi-sidebar .fi-badge {
        background-color: #ffffff !important;
        color: #f59e0b !important;
        border-radius: 9999px !important;
    }

    /* 10. MENGEMBALIKAN POSISI SIDEBAR MENTOK KE ATAS (SEPERTI V3) */
    @media (min-width: 1024px) {
        /* CSS GRID FIX UNTUK LAYOUT SIDEBAR & TOPBAR (hanya di dashboard, abaikan login) */
        body:has(.fi-layout) {
            display: grid !important;
            grid-template-columns: auto 1fr !important;
            grid-template-rows: 4rem 1fr !important; /* Row 1 is Topbar, Row 2 is Main Content */
            height: 100vh !important;
            min-height: 100vh !important;
            overflow: hidden !important;
        }

        .fi-layout {
            display: contents !important;
        }

        /* Sidebar Wrapper */
        .fi-layout > div:nth-child(2) {
            grid-column: 1 !important;
            grid-row: 1 / span 2 !important;
            height: 100vh !important;
            z-index: 40 !important;
        }

        .fi-sidebar {
            height: 100vh !important;
            top: 0 !important;
            z-index: 10 !important;
            box-shadow: 4px 0 10px rgba(0,0,0,0.05) !important;
            padding-top: 0 !important;
        }
        
        /* Topbar Container */
        .fi-topbar-ctn {
            grid-column: 2 !important;
            grid-row: 1 !important;
            height: 4rem !important; /* Fixed height for topbar row */
            width: 100% !important;
            z-index: 100 !important; /* Must be above sidebar so floating button isn't clipped */
            overflow: visible !important; /* Prevent left half from being clipped by the grid cell */
        }

        .fi-topbar {
            z-index: 9999 !important;
            overflow: visible !important; /* Allow the floating button to extend past the topbar edge */
        }

        .fi-topbar-start {
            overflow: visible !important;
        }

        /* Main Content Container (Contains Main ONLY, Topbar is in row 1) */
        .fi-main-ctn {
            grid-column: 2 !important;
            grid-row: 2 !important; /* Start below topbar */
            height: calc(100vh - 4rem) !important; /* Exact remaining height */
            overflow: visible !important; /* Prevent clipping the left half of the button */
            z-index: 45 !important; /* Above sidebar wrapper but below topbar */
            display: flex !important;
            flex-direction: column !important;
            background-color: #f9fafb !important; /* Give the whole right side a background to prevent bleed */
        }

        /* Actual Main Content (Scrollable area) */
        .fi-main {
            overflow-y: auto !important;
            overflow-x: hidden !important;
            flex: 1 1 0% !important;
            height: 100% !important;
            /* Max height removed as container handles it */
        }
        
        /* Force logo back to white */
        .fi-sidebar .fi-logo {
            color: #ffffff !important;
            font-size: 1.25rem !important;
        }
        
        /* Un-hide the sidebar header so logo is visible */
        .fi-sidebar-header {
            display: flex !important;
            height: 4rem !important; /* Standard topbar height */
            align-items: center;
        }

        /* Hide the logo in topbar */
        .fi-topbar-start .fi-logo {
            display: none !important;
        }
        
        /* Hide the mobile hamburger menu on desktop */
        .fi-topbar-open-sidebar-btn {
            display: none !important;
        }

        /* Prevent clipping of floating buttons */
        .fi-topbar,
        .fi-topbar-start {
            overflow: visible !important;
        }
        
        /* Float the collapse button on the sidebar right edge (when expanded) */
        /* left: 0 relative to .fi-topbar automatically puts it on the border regardless of width */
        .fi-topbar-close-collapse-sidebar-btn {
            position: absolute !important;
            top: 4rem !important;
            left: 0 !important;
            transform: translate(-50%, -50%) !important;
            z-index: 200 !important;
            background-color: #ffffff !important;
            color: #f59e0b !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.15) !important;
            width: 2rem !important;
            height: 2rem !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Float the expand button on the sidebar right edge (when collapsed/icons only) */
        .fi-topbar-open-collapse-sidebar-btn {
            position: absolute !important;
            top: 4rem !important;
            left: 0 !important;
            transform: translate(-50%, -50%) !important;
            z-index: 200 !important;
            background-color: #ffffff !important;
            color: #f59e0b !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.15) !important;
            width: 2rem !important;
            height: 2rem !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Adjust icon inside the collapse/expand buttons */
        .fi-topbar-close-collapse-sidebar-btn svg,
        .fi-topbar-open-collapse-sidebar-btn svg {
            color: #f59e0b !important;
            width: 1rem !important;
            height: 1rem !important;
        }
    }

    /* --- DARK MODE FALLBACKS --- */
    html.dark .fi-sidebar {
        background-color: #18181b !important;
    }
    html.dark .fi-sidebar .fi-sidebar-item-active > a,
    html.dark .fi-sidebar .fi-sidebar-item-active {
        background-color: rgba(245, 158, 11, 0.2) !important;
    }
    html.dark .fi-sidebar .fi-sidebar-item-active span {
        color: #fbbf24 !important;
    }
    html.dark .cv-button {
        background-color: transparent !important;
        color: #fbbf24 !important;
        border-color: #fbbf24 !important;
    }
    html.dark .cv-button:hover {
        background-color: #fbbf24 !important;
        color: #000 !important;
    }
    html.dark .stat-card {
        background-color: #1e293b;
        border-color: #334155;
    }
    html.dark .stat-card-value {
        color: #f8fafc;
    }
    html.dark .stat-card-label {
        color: #94a3b8;
    }
</style>
