@props([
    'logo' => null,
    'company' => '',
    'size' => 'main',
    'containerClass' => null,
    'imgClass' => null
])

@php
    if ($size === 'thumb') {
        $defaultContainer = "clay-card w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 transition-all duration-300 overflow-hidden relative border border-amber-400/30 shadow-[0_0_12px_rgba(212,175,55,0.15)]";
        $defaultImgClass = "w-full h-full object-cover scale-[1.12] select-none pointer-events-none rounded-xl";
    } else {
        $defaultContainer = "clay-card w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border border-amber-400/40 flex items-center justify-center flex-shrink-0 relative overflow-hidden shadow-[0_0_25px_rgba(212,175,55,0.2)]";
        $defaultImgClass = "w-full h-full object-cover scale-[1.12] select-none pointer-events-none rounded-2xl";
    }
    $finalContainer = $containerClass ?? $defaultContainer;
    $finalImgClass = $imgClass ?? $defaultImgClass;
@endphp

<div class="{{ $finalContainer }}">
    @if($logo)
        <img src="{{ $logo }}" alt="{{ $company }}" 
             class="{{ $finalImgClass }}"
             oncontextmenu="return false;"
             ondragstart="return false;"
             draggable="false">
    @else
        <span class="text-amber-400 font-heading font-black {{ $size === 'thumb' ? 'text-lg' : 'text-2xl sm:text-3xl' }} select-none">
            {{ strtoupper(substr($company ?: 'C', 0, 1)) }}
        </span>
    @endif
</div>
