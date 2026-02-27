@props([
    'percentage' => 0,
    'size' => 80,
    'variant' => 'light', // light: fond clair + texte sombre | dark: fond sombre + texte blanc
    'label' => null,
    'strokeWidth' => 3,
])

@php
    $pct = (float) $percentage;
    $size = (int) $size;
    $circlePath = 'M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831';
    $lenR = min(25, $pct);
    $lenJ = min(25, max(0, $pct - 25));
    $lenO = min(25, max(0, $pct - 50));
    $lenV = min(25, max(0, $pct - 75));
    $uid = 'cc-' . uniqid();
    $trackColor = $variant === 'dark' ? '#475569' : '#e2e8f0';
    $textClass = $variant === 'dark' ? 'text-white' : 'text-slate-700';
    $labelClass = $variant === 'dark' ? 'text-slate-400' : 'text-slate-500';
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center']) }}>
    <div class="relative inline-flex flex-shrink-0" style="width: {{ $size }}px; height: {{ $size }}px;">
        <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36" aria-hidden="true">
            <defs>
                <linearGradient id="{{ $uid }}-1" gradientUnits="userSpaceOnUse" x1="18" y1="2" x2="34" y2="18">
                    <stop offset="0%" stop-color="#ef4444"/><stop offset="35%" stop-color="#fbbf24"/><stop offset="100%" stop-color="#fbbf24"/>
                </linearGradient>
                <linearGradient id="{{ $uid }}-2" gradientUnits="userSpaceOnUse" x1="34" y1="18" x2="18" y2="34">
                    <stop offset="0%" stop-color="#fbbf24"/><stop offset="35%" stop-color="#f97316"/><stop offset="100%" stop-color="#f97316"/>
                </linearGradient>
                <linearGradient id="{{ $uid }}-3" gradientUnits="userSpaceOnUse" x1="18" y1="34" x2="2" y2="18">
                    <stop offset="0%" stop-color="#f97316"/><stop offset="35%" stop-color="#ea580c"/><stop offset="100%" stop-color="#ea580c"/>
                </linearGradient>
                <linearGradient id="{{ $uid }}-4" gradientUnits="userSpaceOnUse" x1="2" y1="18" x2="18" y2="2">
                    <stop offset="0%" stop-color="#ea580c"/><stop offset="35%" stop-color="#10b981"/><stop offset="100%" stop-color="#10b981"/>
                </linearGradient>
            </defs>
            <path stroke="{{ $trackColor }}" stroke-width="{{ $strokeWidth }}" fill="none" d="{{ $circlePath }}" />
            @if($lenR > 0)<path stroke="url(#{{ $uid }}-1)" stroke-width="{{ $strokeWidth }}" fill="none" stroke-linecap="butt" stroke-dasharray="{{ $lenR }}, 100" stroke-dashoffset="0" d="{{ $circlePath }}" />@endif
            @if($lenJ > 0)<path stroke="url(#{{ $uid }}-2)" stroke-width="{{ $strokeWidth }}" fill="none" stroke-linecap="butt" stroke-dasharray="{{ $lenJ }}, 100" stroke-dashoffset="-25" d="{{ $circlePath }}" />@endif
            @if($lenO > 0)<path stroke="url(#{{ $uid }}-3)" stroke-width="{{ $strokeWidth }}" fill="none" stroke-linecap="butt" stroke-dasharray="{{ $lenO }}, 100" stroke-dashoffset="-50" d="{{ $circlePath }}" />@endif
            @if($lenV > 0)<path stroke="url(#{{ $uid }}-4)" stroke-width="{{ $strokeWidth }}" fill="none" stroke-linecap="butt" stroke-dasharray="{{ $lenV }}, 100" stroke-dashoffset="-75" d="{{ $circlePath }}" />@endif
        </svg>
        <span class="absolute inset-0 flex items-center justify-center text-sm font-bold {{ $textClass }}" aria-label="{{ round($pct) }} pour cent">{{ round($pct) }}%</span>
    </div>
    @if($label !== null)
        <span class="text-xs mt-0.5 whitespace-nowrap {{ $labelClass }}">{{ $label }}</span>
    @endif
</div>
