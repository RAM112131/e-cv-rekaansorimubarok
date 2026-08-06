<div data-widget="stats" style="display:flex;gap:0.75rem;width:100%;box-sizing:border-box;">
    @php
        $totalVisitors = \App\Models\Visitor::count();
        $todayVisitors = \App\Models\Visitor::whereDate('visited_at', today())->count();
        $unreadMessages = \App\Models\Message::where('is_read', false)->count();
        $totalProjects = \App\Models\Project::count();
        $totalSkills = \App\Models\Skill::count();
        $totalCerts = \App\Models\Certificate::count();

        $cards = [
            [
                'label'   => 'Total Pengunjung',
                'value'   => number_format($totalVisitors),
                'trend'   => '+' . $todayVisitors . ' hari ini',
                'up'      => true,
                'color'   => '#f59e0b',
                'fill'    => 'rgba(245,158,11,0.12)',
                'path'    => 'M0,40 C10,35 20,20 30,25 C40,30 50,10 60,15 C70,20 80,5 90,8 L90,55 L0,55 Z',
                'line'    => 'M0,40 C10,35 20,20 30,25 C40,30 50,10 60,15 C70,20 80,5 90,8',
            ],
            [
                'label'   => 'Pesan Belum Dibaca',
                'value'   => (string) $unreadMessages,
                'trend'   => $unreadMessages > 0 ? 'Perlu respons' : 'Semua terbaca',
                'up'      => $unreadMessages === 0,
                'color'   => '#f59e0b',
                'fill'    => 'rgba(245,158,11,0.12)',
                'path'    => 'M0,30 C15,28 25,40 40,35 C55,30 65,20 80,22 C85,23 88,22 90,20 L90,55 L0,55 Z',
                'line'    => 'M0,30 C15,28 25,40 40,35 C55,30 65,20 80,22 C85,23 88,22 90,20',
            ],
            [
                'label'   => 'Total Proyek',
                'value'   => (string) $totalProjects,
                'trend'   => 'Portofolio aktif',
                'up'      => true,
                'color'   => '#f59e0b',
                'fill'    => 'rgba(245,158,11,0.12)',
                'path'    => 'M0,45 C20,40 30,25 45,28 C60,31 70,15 90,10 L90,55 L0,55 Z',
                'line'    => 'M0,45 C20,40 30,25 45,28 C60,31 70,15 90,10',
            ],
            [
                'label'   => 'Skill & Sertifikat',
                'value'   => (string) $totalSkills,
                'trend'   => $totalCerts . ' Sertifikat',
                'up'      => true,
                'color'   => '#f59e0b',
                'fill'    => 'rgba(245,158,11,0.12)',
                'path'    => 'M0,50 C10,45 20,35 35,30 C50,25 60,32 70,20 C80,12 85,10 90,8 L90,55 L0,55 Z',
                'line'    => 'M0,50 C10,45 20,35 35,30 C50,25 60,32 70,20 C80,12 85,10 90,8',
            ],
        ];
    @endphp

    @foreach($cards as $card)
    <div class="stat-card"
    onmouseover="this.style.boxShadow='0 6px 20px rgba(0,0,0,0.12)';this.style.transform='translateY(-1px)'"
    onmouseout="this.style.boxShadow='';this.style.transform='translateY(0)'"
    >
        {{-- Left: Label + Value + Trend --}}
        <div style="display:flex;flex-direction:column;gap:0.25rem;min-width:0;overflow:hidden;">
            <span class="stat-card-label">
                {{ $card['label'] }}
            </span>

            <span class="stat-card-value">
                {{ $card['value'] }}
            </span>

            <span style="
                display:inline-flex;align-items:center;gap:0.25rem;
                font-size:0.7rem;font-weight:700;
                color:{{ $card['up'] ? '#f59e0b' : '#475569' }};
                overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
            ">
                @if($card['up'])
                <svg xmlns="http://www.w3.org/2000/svg" style="width:0.65rem;height:0.65rem;flex-shrink:0;" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd"/></svg>
                @else
                <svg xmlns="http://www.w3.org/2000/svg" style="width:0.65rem;height:0.65rem;flex-shrink:0;" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z" clip-rule="evenodd"/></svg>
                @endif
                {{ $card['trend'] }}
            </span>
        </div>

        {{-- Right: Mini Sparkline SVG --}}
        <div style="flex-shrink:0;width:90px;height:55px;overflow:hidden;">
            <svg viewBox="0 0 90 55" xmlns="http://www.w3.org/2000/svg" style="width:100%;height:100%;">
                <path d="{{ $card['path'] }}" fill="{{ $card['fill'] }}" />
                <path d="{{ $card['line'] }}" fill="none" stroke="{{ $card['color'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                {{-- Dot at end --}}
                <circle cx="90" cy="{{ Str::afterLast($card['line'], ' ') }}" r="3" fill="{{ $card['color'] }}" opacity="0"/>
            </svg>
        </div>
    </div>
    @endforeach
</div>
