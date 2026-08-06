<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CV – {{ $user->name }}</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

  :root {
    --navy:   #0B2147;
    --navy2:  #152D5B;
    --teal:   #00B4D8;
    --teal-lt:#E5F7FB;
    --white:  #FFFFFF;
    --bg:     #EEF3FA;
    --sidebar:#F4F7FC;
    --line:   #DDE5EF;
    --text:   #1E2A3A;
    --muted:  #607080;
    --sm:     11px;
    --md:     13px;
  }

  body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--bg);
    color: var(--text);
    padding: 40px 16px 80px;
    min-height: 100vh;
  }

  /* ─── A4 SHELL ─── */
  .cv {
    width: 794px;
    min-height: 1122px;
    height: auto;
    margin: 0 auto;
    background: var(--white);
    box-shadow: 0 8px 40px rgba(11,33,71,.18);
    display: flex;
    flex-direction: column;
    overflow: visible;
  }

  /* ─── HEADER ─── */
  .cv-header {
    background: var(--navy);
    padding: 16px 30px;
    display: flex;
    align-items: center;
    gap: 20px;
    position: relative;
    overflow: hidden;
    flex-shrink: 0;
  }
  .cv-header::before {
    content:'';
    position:absolute; right:-60px; top:-80px;
    width:280px; height:280px; border-radius:50%;
    border:45px solid rgba(0,180,216,.07);
  }
  .cv-header::after {
    content:'';
    position:absolute; right:100px; top:30px;
    width:160px; height:160px; border-radius:50%;
    border:25px solid rgba(0,180,216,.05);
  }

  .avatar {
    width:90px; height:90px; border-radius:50%;
    border:3px solid var(--teal);
    background: var(--navy2);
    flex-shrink:0; overflow:hidden;
    display:flex; align-items:center; justify-content:center;
    font-size:36px; color:var(--teal);
    position:relative; z-index:1;
  }
  .avatar img { width:100%; height:100%; object-fit:cover; }

  .header-text { flex:1; position:relative; z-index:1; }
  .header-text h1 {
    font-size:22px; font-weight:800; color:#fff;
    letter-spacing:-.5px; line-height:1.1;
    margin-bottom:2px;
  }
  .header-text .subtitle {
    font-size:10px; font-weight:600; color:var(--teal);
    letter-spacing:1.2px; text-transform:uppercase;
    margin-bottom:8px;
  }
  .contacts {
    display:flex; flex-wrap:wrap; gap:8px 14px;
  }
  .contacts a, .contact-item {
    display:flex; align-items:center; gap:5px;
    color:rgba(255,255,255,.72); font-size:11px;
    text-decoration:none;
  }
  .contacts a:hover { color:var(--teal); }
  .contacts svg { flex-shrink:0; }

  /* ─── BODY ─── */
  .cv-body { display:flex; flex:1; }

  /* ─── SIDEBAR ─── */
  .sidebar {
    width:228px; flex-shrink:0;
    background:var(--sidebar);
    border-right:1px solid var(--line);
    padding:14px 18px;
  }

  .s-section { margin-bottom:10px; }

  .s-title {
    font-size:9.5px; font-weight:800; letter-spacing:1.8px;
    text-transform:uppercase; color:var(--navy);
    padding-bottom:3px; border-bottom:2px solid var(--teal);
    margin-bottom:6px;
  }

  /* Contact items in sidebar */
  .s-contact { display:flex; align-items:flex-start; gap:8px; margin-bottom:8px; }
  .s-contact svg { flex-shrink:0; margin-top:1px; color:var(--teal); }
  .s-contact span { font-size:11px; color:var(--muted); line-height:1.35; }
  .s-contact a { font-size:11px; color:var(--teal); text-decoration:none; word-break:break-all; }

  /* About */
  .s-about { font-size:9.5px; color:var(--muted); line-height:1.45; }

  /* Skill bars */
  .skill { margin-bottom:7px; }
  .skill-label { display:flex; justify-content:space-between; font-size:9.5px; margin-bottom:3px; }
  .skill-label span:first-child { color:var(--text); font-weight:500; }
  .skill-label span:last-child  { color:var(--muted); font-size:9px; }
  .skill-track { height:5px; background:#D8E3EE; border-radius:4px; overflow:hidden; }
  .skill-fill  { height:100%; border-radius:4px; background:linear-gradient(90deg,var(--navy) 0%,var(--teal) 100%); }

  /* Tags */
  .tags { display:flex; flex-wrap:wrap; gap:4px; }
  .tag {
    font-size:10px; font-weight:600; padding:3px 8px;
    border-radius:20px; background:#DDE8F5; color:var(--navy);
  }

  /* Language dots */
  .lang-row { display:flex; justify-content:space-between; align-items:center; margin-bottom:6px; }
  .lang-name { font-size:11px; font-weight:500; color:var(--text); }
  .lang-dots { display:flex; gap:4px; }
  .dot { width:7px; height:7px; border-radius:50%; background:#D8E3EE; }
  .dot.on { background:var(--teal); }

  /* Certs */
  .cert { margin-bottom:8px; }
  .cert-name { font-size:11px; font-weight:600; color:var(--text); margin-bottom:1px; }
  .cert-by   { font-size:10px; color:var(--muted); }

  /* ─── MAIN ─── */
  .main { flex:1; padding:14px 20px; }

  .m-section { margin-bottom:10px; }

  .m-title {
    font-size:9.5px; font-weight:800; letter-spacing:1.8px;
    text-transform:uppercase; color:var(--navy);
    padding-bottom:3px; border-bottom:2px solid var(--teal);
    margin-bottom:6px;
    display:flex; align-items:center; gap:6px;
  }
  .m-title svg { color:var(--teal); }

  /* Timeline */
  .tl-item {
    position:relative;
    padding-left:16px;
    margin-bottom:6px;
  }
  .tl-item::before {
    content:''; position:absolute;
    left:0; top:5px;
    width:7px; height:7px; border-radius:50%;
    background:var(--teal);
    box-shadow:0 0 0 2px rgba(0,180,216,.15);
  }
  .tl-item::after {
    content:''; position:absolute;
    left:3px; top:15px;
    width:1px; height:calc(100% + 2px);
    background:var(--line);
  }
  .tl-item:last-child::after { display:none; }

  .tl-head { display:flex; justify-content:space-between; align-items:flex-start; gap:6px; }
  .tl-name { font-size:11.5px; font-weight:700; color:var(--navy); line-height:1.2; }
  .tl-badge {
    font-size:9px; font-weight:600; color:var(--teal);
    background:var(--teal-lt); padding:2px 7px; border-radius:12px;
    white-space:nowrap; flex-shrink:0;
  }
  .tl-sub { font-size:10px; color:var(--teal); font-weight:600; margin:1px 0 3px; }
  .tl-desc { font-size:9.5px; color:var(--muted); line-height:1.35; white-space:pre-line; }
  .tl-desc ul { padding-left:12px; }
  .tl-desc li { margin-bottom:2px; }

  /* Tech stack pills inside items */
  .stack { display:flex; flex-wrap:wrap; gap:4px; margin-top:5px; }
  .stack span {
    font-size:9.5px; font-weight:600;
    background:#EBF2FA; color:var(--navy);
    padding:2px 7px; border-radius:8px;
  }

  /* GPA / info row */
  .info-row { display:flex; gap:14px; margin-top:3px; flex-wrap:wrap; }
  .info-chip {
    display:flex; align-items:center; gap:4px;
    font-size:10.5px; color:var(--muted);
  }
  .info-chip svg { color:var(--teal); }
  .info-chip strong { color:var(--text); }

  /* ─── FOOTER STRIP ─── */
  .cv-footer {
    background:var(--navy);
    padding:8px 30px;
    display:flex; justify-content:center;
    gap:24px;
    flex-shrink: 0;
    margin-top: auto;
  }
  .cv-footer span { font-size:10px; color:rgba(255,255,255,.45); }
  .cv-footer a { font-size:10px; color:rgba(255,255,255,.45); text-decoration:none; }
  .cv-footer a:hover { color:var(--teal); }

  /* ─── PRINT BUTTON ─── */
  .fab {
    position:fixed; bottom:28px; right:28px;
    background:#f59e0b; color:#fff;
    border:none; border-radius:50px;
    padding:12px 22px; cursor:pointer;
    font-family:'Plus Jakarta Sans',sans-serif;
    font-size:13px; font-weight:700;
    display:flex; align-items:center; gap:8px;
    box-shadow:0 6px 24px rgba(245, 158, 11, .35);
    transition:background .2s;
    z-index:999;
  }
  .fab:hover { background:#d97706; }

  /* ─── PRINT ─── */
  @media print {
    body { background:#fff; padding:0; }
    .cv  { box-shadow:none; width:100%; min-height:auto; height:auto; overflow:visible; }
    .fab { display:none; }
    @page { size:A4; margin:0; }
  }

  /* PDF export helper */
  .pdf-export {
    width: 794px !important;
    height: auto !important;
    min-height: 1122px !important;
    margin: 0 !important;
    box-shadow: none !important;
    transform: none !important;
    overflow: visible !important;
  }
</style>
</head>
<body>

@php
    $cvData = $user->cv_data ?? [];
    $cvSkills = $cvData['cv_skills'] ?? [
        ['name' => 'PHP / Laravel', 'level' => 'Dasar', 'percentage' => 40],
        ['name' => 'MySQL', 'level' => 'Dasar', 'percentage' => 35],
        ['name' => 'HTML & CSS', 'level' => 'Menengah', 'percentage' => 60],
        ['name' => 'JavaScript', 'level' => 'Dasar', 'percentage' => 25],
        ['name' => 'Git & GitHub', 'level' => 'Menengah', 'percentage' => 70],
        ['name' => 'Python', 'level' => 'Dasar', 'percentage' => 45],
    ];
    $cvTools = $cvData['cv_tools'] ?? [
        ['name' => 'Figma'],
        ['name' => 'VS Code'],
        ['name' => 'Antigravity'],
        ['name' => 'Laragon'],
        ['name' => 'Tailwind CSS'],
        ['name' => 'Bootstrap'],
    ];
    $cvLanguages = $cvData['cv_languages'] ?? [
        ['name' => 'Indonesia', 'rating' => 5],
        ['name' => 'Inggris', 'rating' => 3],
    ];
    $cvCertifications = $cvData['cv_certifications'] ?? [
        ['name' => 'Belajar Dasar Pemrograman Web', 'issuer' => 'Dicoding', 'year' => '2023'],
        ['name' => 'Database Design & SQL', 'issuer' => 'MySkill', 'year' => '2023'],
        ['name' => 'Laravel Framework', 'issuer' => 'BuildWith Angga', 'year' => '2024'],
        ['name' => 'Google IT Support', 'issuer' => 'Google / Coursera', 'year' => '2024'],
    ];
    $cvProjects = $cvData['cv_projects'] ?? [
        [
            'name' => 'Sistem Informasi Bersih Bumi (Manajemen Pengelolaan Sampah)',
            'badge' => '2026',
            'sub' => 'Tugas Kuliah',
            'desc' => "• Membangun platform manajemen iuran pengangkutan sampah berbasis web untuk membantu RT/RW mengelola data warga dan pembayaran secara digital.\n• Mengintegrasikan Midtrans Payment Gateway (QRIS & Virtual Account) untuk kemudahan transaksi non-tunai.\n• Menerapkan autentikasi multi-role (Admin, Petugas, Warga) menggunakan Laravel Jetstream.",
        ],
        [
            'name' => 'Website Smart Tourism Guide Kota Banjar',
            'badge' => '2025',
            'sub' => 'Tugas Kuliah',
            'desc' => "• Mengembangkan Web Pemandu Wisata Online Berbasis QR Code untuk meningkatkan aksesibilitas informasi wisata.\n• Menyediakan sistem pemesanan tiket secara online",
        ],
        [
            'name' => 'Sistem Informasi Pemilihan OSIS',
            'badge' => '2025',
            'sub' => 'Proyek Mandiri',
            'desc' => 'Membangun website pemilihan OSIS responsif dengan fitur pemilihan online dan manajemen kandidat.',
        ],
        [
            'name' => 'Mengikuti Kegiatan Webinar "Membuat Sistem Task Manager Team dengan Laravel"',
            'badge' => '2025',
            'sub' => 'Webinar',
            'desc' => 'Mengikuti webinar yang diselenggarakan oleh ID-Networkers. Mempelajari dasar-dasar pengembangan sistem manajemen tugas tim menggunakan framework Laravel, termasuk struktur proyek, manajemen database, autentikasi, dan implementasi alur kerja kolaboratif.',
        ],
        [
            'name' => 'Staf Gudang di Anyar CCTV Bandung',
            'badge' => 'September 2023 - Juli 2024',
            'sub' => 'Pengalaman Kerja',
            'desc' => 'Melakukan tugas-tugas administrasi gudang secara menyeluruh, termasuk inventarisasi barang, pencatatan masuk-keluar stok, pengelolaan persediaan, dan pengaturan tata letak penyimpanan agar efisien. Berkoordinasi dengan tim operasional, memastikan kelengkapan barang, membantu proses pengiriman, dan menyusun laporan bulanan untuk mendukung kelancaran aktivitas gudang.',
        ],
    ];
    $cvOrgs = $cvData['cv_orgs'] ?? [
        [
            'name' => 'Divisi HUMAS – Himpunan Mahasiswa Sistem Informasi',
            'badge' => 'Januari - Desember 2025',
            'sub' => 'Pengurus · UBSI Kota Tasikmalaya',
            'desc' => 'Bertugas membangun dan menjaga citra organisasi melalui komunikasi eksternal dan internal, mengelola hubungan dengan media dan platform digital.',
        ],
        [
            'name' => 'Panitia Bakti Sosial Kemasyarakatan',
            'badge' => '4-24 Agustus 2025',
            'sub' => 'Divisi Konsumsi · KMC Galuh Taruna Bandung',
            'desc' => 'Bertanggung jawab atas pengelolaan konsumsi, termasuk perencanaan menu, koordinasi katering, dan distribusi makanan untuk seluruh panitia atau dalam kegiatan.',
        ],
    ];

    if (!function_exists('formatCvDescription')) {
        function formatCvDescription(?string $text): string
        {
            if (blank($text)) {
                return '';
            }

            $lines = array_filter(array_map('trim', explode("\n", $text)));
            if (empty($lines)) {
                return '';
            }

            $hasBullets = false;
            foreach ($lines as $line) {
                if (preg_match('/^[\bullet\-\*\•\d+\.]/u', $line)) {
                    $hasBullets = true;
                    break;
                }
            }

            if ($hasBullets) {
                $html = '<ul>';
                foreach ($lines as $line) {
                    $cleanLine = preg_replace('/^[\bullet\-\*\•\d+\.]+\s*/u', '', $line);
                    if (!empty($cleanLine)) {
                        $html .= '<li>' . e($cleanLine) . '</li>';
                    }
                }
                $html .= '</ul>';
                return $html;
            }

            return '<p>' . nl2br(e($text)) . '</p>';
        }
    }
@endphp

<div class="cv">

  <!-- ════ HEADER ════ -->
  <header class="cv-header">
    <div class="avatar">
      @if($user->cv_avatar)
        <img src="{{ asset('storage/' . $user->cv_avatar) }}" alt="{{ $user->name }}" width="90" height="90" style="object-fit:cover; border-radius:50%;">
      @elseif($user->avatar)
        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" width="90" height="90" style="object-fit:cover; border-radius:50%;">
      @else
        <span>{{ strtoupper(substr($user->name, 0, 2)) }}</span>
      @endif
    </div>
    <div class="header-text">
      <h1>{{ strtoupper($cvData['cv_name'] ?? $user->name) }}</h1>
      <p class="subtitle">{{ $user->cv_subtitle ?? 'Mahasiswa Sistem Informasi | UBSI Kota Tasikmalaya' }}</p>
      <div class="contacts">
        @if($user->phone)
        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $user->phone) }}">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          {{ $user->phone }}
        </a>
        @endif

        <a href="mailto:{{ $user->email }}">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          {{ $user->email }}
        </a>

        @php
            $linkedinUrl = $user->linkedin_url ?? ($socialLinks->where('platform', 'linkedin')->first()?->url ?? 'https://www.linkedin.com/in/reka-ansori-mubarok-a5508427a');
        @endphp

        @if($linkedinUrl)
        <a href="{{ $linkedinUrl }}" target="_blank">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
          {{ parse_url($linkedinUrl, PHP_URL_HOST) }}{{ parse_url($linkedinUrl, PHP_URL_PATH) }}
        </a>
        @endif

        @if($user->location)
        <span class="contact-item">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          {{ $user->location }}
        </span>
        @endif
      </div>
    </div>
  </header>

  <!-- ════ BODY ════ -->
  <div class="cv-body">

    <!-- ── SIDEBAR ── -->
    <aside class="sidebar">

      <!-- Profil Singkat -->
      <div class="s-section">
        <div class="s-title">Tentang Saya</div>
        <p class="s-about">
          {{ $user->cv_about ?? 'Mahasiswa aktif Sistem Informasi di UBSI yang antusias dalam pengembangan web. Memiliki pengalaman membangun aplikasi berbasis Laravel dengan integrasi pembayaran digital. Berkomitmen untuk terus belajar dan berkontribusi di lingkungan kerja profesional.' }}
        </p>
      </div>

      <!-- Keahlian Teknis -->
      <div class="s-section">
        <div class="s-title">Keahlian Teknis</div>

        @forelse($skills as $skill)
        @php
            $levelLabel = is_numeric($skill->level) 
                ? ($skill->level >= 80 ? 'Lanjutan' : ($skill->level >= 50 ? 'Menengah' : 'Dasar')) 
                : ($skill->level ?? 'Menengah');
            $percentage = is_numeric($skill->level) ? $skill->level : 50;
        @endphp
        <div class="skill">
          <div class="skill-label">
            <span>{{ $skill->name }}</span>
            <span>{{ $levelLabel }}</span>
          </div>
          <div class="skill-track">
            <div class="skill-fill" style="width: {{ $percentage }}%"></div>
          </div>
        </div>
        @empty
        <div class="skill">
          <div class="skill-label"><span>PHP / Laravel</span><span>Dasar</span></div>
          <div class="skill-track"><div class="skill-fill" style="width:40%"></div></div>
        </div>
        @endforelse
      </div>

      <!-- Tools & Platform -->
      <div class="s-section">
        <div class="s-title">Tools &amp; Platform</div>
        <div class="tags">
          @foreach($cvTools as $tool)
          <span class="tag">{{ $tool['name'] }}</span>
          @endforeach
        </div>
      </div>

      <!-- Bahasa (Opsional) -->
      @if(!empty($cvLanguages) && count($cvLanguages) > 0)
      <div class="s-section">
        <div class="s-title">Bahasa</div>
        @foreach($cvLanguages as $lang)
        @php $rating = (int) ($lang['rating'] ?? 5); @endphp
        <div class="lang-row">
          <span class="lang-name">{{ $lang['name'] }}</span>
          <div class="lang-dots">
            @for($i = 1; $i <= 5; $i++)
            <div class="dot {{ $i <= $rating ? 'on' : '' }}"></div>
            @endfor
          </div>
        </div>
        @endforeach
      </div>
      @endif

      <!-- Sertifikasi (Opsional) -->
      @if(!empty($cvCertifications) && count($cvCertifications) > 0)
      <div class="s-section">
        <div class="s-title">Sertifikasi</div>
        @foreach($cvCertifications as $cert)
        <div class="cert">
          <div class="cert-name">{{ $cert['name'] }}</div>
          <div class="cert-by">{{ $cert['issuer'] }} @if(!empty($cert['year'])) · {{ $cert['year'] }} @endif</div>
        </div>
        @endforeach
      </div>
      @elseif($certificates->count() > 0)
      <div class="s-section">
        <div class="s-title">Sertifikasi</div>
        @foreach($certificates as $cert)
        <div class="cert">
          <div class="cert-name">{{ $cert->title }}</div>
          <div class="cert-by">{{ $cert->issuer }} @if($cert->issue_date) · {{ \Carbon\Carbon::parse($cert->issue_date)->format('Y') }} @endif</div>
        </div>
        @endforeach
      </div>
      @endif

    </aside>

    <!-- ── MAIN ── -->
    <main class="main">

      <!-- Pendidikan -->
      <div class="m-section">
        <div class="m-title">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
          Pendidikan
        </div>

        @forelse($educations as $edu)
        <div class="tl-item">
          <div class="tl-head">
            <div class="tl-name">{{ $edu->institution }}</div>
            <div class="tl-badge">{{ $edu->start_year }} – {{ $edu->end_year ?? 'Sekarang' }}</div>
          </div>
          @if($edu->degree || $edu->major)
          <div class="tl-sub">{{ $edu->degree }} {{ $edu->major ? '· ' . $edu->major : '' }}</div>
          @endif

          @if($edu->semester || $edu->gpa)
          <div class="info-row">
            @if($edu->gpa)
            <div class="info-chip">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
              <span>IPK: <strong>{{ $edu->gpa }}</strong></span>
            </div>
            @endif
            @if($edu->semester)
            <div class="info-chip">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              <span>Semester: <strong>{{ $edu->semester }}</strong></span>
            </div>
            @endif
          </div>
          @endif
        </div>
        @empty
        <div class="tl-item">
          <div class="tl-head">
            <div class="tl-name">Universitas Bina Sarana Informatika (UBSI) Kota Tasikmalaya</div>
            <div class="tl-badge">2024 – Sekarang</div>
          </div>
          <div class="tl-sub">S1 Sistem Informasi &nbsp;·&nbsp; Kampus Kota Tasikmalaya</div>
          <div class="info-row">
            <div class="info-chip">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              <span>Semester: <strong>4</strong></span>
            </div>
          </div>
        </div>

        <div class="tl-item">
          <div class="tl-head">
            <div class="tl-name">MAN 5 Ciamis</div>
            <div class="tl-badge">2020 – 2023</div>
          </div>
          <div class="tl-sub">MIPA</div>
        </div>
        @endforelse
      </div>

      <!-- Proyek & Pengalaman -->
      <div class="m-section">
        <div class="m-title">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
          Proyek &amp; Pengalaman
        </div>

        @foreach($cvProjects as $project)
        <div class="tl-item">
          <div class="tl-head">
            <div class="tl-name">{{ $project['name'] }}</div>
            @if(!empty($project['badge']))
            <div class="tl-badge">{{ $project['badge'] }}</div>
            @endif
          </div>
          @if(!empty($project['sub']))
          <div class="tl-sub">{{ $project['sub'] }}</div>
          @endif
          @if(!empty($project['desc']))
          <div class="tl-desc">
            {!! formatCvDescription($project['desc']) !!}
          </div>
          @endif
        </div>
        @endforeach
      </div>

      <!-- Organisasi & Kepanitiaan -->
      @if(count($cvOrgs) > 0)
      <div class="m-section">
        <div class="m-title">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          Organisasi &amp; Kepanitiaan
        </div>

        @foreach($cvOrgs as $org)
        <div class="tl-item">
          <div class="tl-head">
            <div class="tl-name">{{ $org['name'] }}</div>
            @if(!empty($org['badge']))
            <div class="tl-badge">{{ $org['badge'] }}</div>
            @endif
          </div>
          @if(!empty($org['sub']))
          <div class="tl-sub">{{ $org['sub'] }}</div>
          @endif
          @if(!empty($org['desc']))
          <div class="tl-desc">
            {!! formatCvDescription($org['desc']) !!}
          </div>
          @endif
        </div>
        @endforeach
      </div>
      @endif

    </main>
  </div>

  <!-- ════ FOOTER ════ -->
  <footer class="cv-footer">
    <span>Dibuat dengan sepenuh hati · <strong style="color:rgba(255,255,255,.6)">{{ $cvData['cv_name'] ?? $user->name }}</strong></span>
    <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
  </footer>

</div>

<!-- Print / Download PDF Button -->
<button class="fab" onclick="generatePDF()">
  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
  Cetak / Simpan PDF
</button>

<!-- html2pdf library - handles PDF generation with proper scaling -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
  function generatePDF() {
    var element = document.querySelector('.cv');
    var currentScrollY = window.scrollY || document.documentElement.scrollTop;

    // Temporarily scroll to top so html2canvas renders clean canvas without scroll offsets
    window.scrollTo(0, 0);
    element.classList.add('pdf-export');

    var opt = {
      margin:         0,
      filename:       '{{ Str::slug($cvData["cv_name"] ?? $user->name) }}-CV.pdf',
      image:          { type: 'jpeg', quality: 0.98 },
      html2canvas:    {
        scale: 2,
        scrollX: 0,
        scrollY: 0,
        useCORS: true,
        logging: false
      },
      jsPDF:          { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };

    html2pdf().set(opt).from(element).save().then(function() {
      element.classList.remove('pdf-export');
      window.scrollTo(0, currentScrollY);
    }).catch(function(err){
      console.error('Error generating PDF:', err);
      element.classList.remove('pdf-export');
      window.scrollTo(0, currentScrollY);
    });
  }
</script>

</body>
</html>
