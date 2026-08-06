{{-- Hanya tampilkan di halaman dengan layout "Simple" (misal: halaman profil & login) --}}
@if (request()->routeIs('filament.admin.auth.profile'))
    <div class="fixed top-4 left-4 z-50" style="position: fixed; top: 1rem; left: 1rem; z-index: 50;">
        <a href="{{ url('/admin') }}"
           style="display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; border-radius: 0.5rem; background-color: #ffffff; color: #374151; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); text-decoration: none; border: 1px solid #e5e7eb; transition: all 0.2s;"
           onmouseover="this.style.backgroundColor='#f3f4f6'"
           onmouseout="this.style.backgroundColor='#ffffff'">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;">
                <path d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
            </svg>
            Kembali ke Dashboard
        </a>
    </div>
@endif
