@props([
    'links' => null,
    'wrapperClass' => 'flex items-center gap-3 flex-wrap',
    'itemClass' => 'clay-circle w-11 h-11 flex items-center justify-center text-amber-300 hover:text-white transition-all hover:scale-110 cursor-pointer',
    'iconClass' => 'w-5 h-5 fill-current'
])

@php
    $defaultLinks = [
        ['platform' => 'GitHub', 'url' => 'https://github.com'],
        ['platform' => 'LinkedIn', 'url' => 'https://linkedin.com'],
        ['platform' => 'Behance', 'url' => 'https://behance.net'],
        ['platform' => 'Instagram', 'url' => 'https://instagram.com'],
        ['platform' => 'Telegram', 'url' => 'https://t.me'],
    ];

    $items = [];
    if (isset($links) && (is_array($links) || (is_object($links) && count($links) > 0))) {
        foreach ($links as $l) {
            $items[] = [
                'platform' => is_object($l) ? $l->platform : ($l['platform'] ?? ''),
                'url' => is_object($l) ? $l->url : ($l['url'] ?? '#'),
            ];
        }
    } else {
        $items = $defaultLinks;
    }
@endphp

<div class="{{ $wrapperClass }}">
    @foreach($items as $item)
        <a href="{{ $item['url'] }}" 
           target="_blank" 
           rel="noopener noreferrer"
           class="{{ $itemClass }}" 
           title="{{ $item['platform'] }}">
            <x-social-icon :platform="$item['platform']" :class="$iconClass" />
        </a>
    @endforeach
</div>
