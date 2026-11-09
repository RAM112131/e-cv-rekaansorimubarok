<div style="position:relative;overflow:hidden;border-radius:1rem;border:1px solid rgba(226,232,240,0.8);background:#fff;padding:1.5rem;box-shadow:0 1px 4px rgba(0,0,0,0.05);" class="dark:bg-slate-900 dark:border-slate-800">
    <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:1.5rem;">
        {{-- Left: Greeting & Info --}}
        <div style="display:flex;flex-direction:column;gap:0.5rem;max-width:36rem;">
            <div style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.25rem 0.75rem;border-radius:9999px;font-size:0.75rem;font-weight:600;letter-spacing:0.03em;background:rgba(245,158,11,0.1);color:#D97706;border:1px solid rgba(245,158,11,0.2);">
                <span style="width:0.5rem;height:0.5rem;border-radius:9999px;background:#F59E0B;display:inline-block;animation:pulse 2s cubic-bezier(0.4,0,0.6,1) infinite;"></span>
                Portofolio Aktif &amp; Publik
            </div>
            <h2 style="font-size:1.5rem;font-weight:700;color:#0f172a;letter-spacing:-0.02em;margin:0;" class="dark:text-slate-100">
                Selamat Datang,                 {{ auth()->user()->portfolioProfile?->name ?? 'Admin' }}! 👋
            </h2>
            <p style="font-size:0.875rem;color:#64748b;line-height:1.6;margin:0;">
                {{ auth()->user()->portfolioProfile?->headline ?? 'Kelola karya, keahlian, pengalaman, dan sertifikat profesional Anda.' }}
            </p>
        </div>

        {{-- Right: Quick Actions --}}
        <div style="display:flex;flex-wrap:wrap;align-items:center;gap:0.75rem;">
            <a href="{{ url('/admin/projects') }}"
               style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.625rem 1.125rem;border-radius:9999px;font-size:0.8rem;font-weight:700;color:#fff;background:linear-gradient(135deg,#F59E0B,#D97706);box-shadow:0 4px 12px rgba(245,158,11,0.35);text-decoration:none;transition:all 0.2s ease;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Tambah Proyek
            </a>

            <a href="{{ url('/admin/messages') }}"
               style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.625rem 1.125rem;border-radius:9999px;font-size:0.8rem;font-weight:600;color:#334155;background:#f1f5f9;border:1px solid #e2e8f0;text-decoration:none;transition:all 0.2s ease;" class="dark:bg-slate-800 dark:text-slate-200 dark:border-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem;flex-shrink:0;color:#F59E0B;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Pesan Masuk
            </a>

            <a href="{{ url('/') }}" target="_blank"
               style="display:inline-flex;align-items:center;gap:0.4rem;padding:0.625rem 1rem;border-radius:9999px;font-size:0.8rem;font-weight:600;color:#94a3b8;text-decoration:none;transition:all 0.2s ease;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Pratinjau Web
            </a>
        </div>
    </div>
</div>
