@props([
    'src' => '',
    'alt' => '',
    'aspect' => 'video', // 'video' (16:9 Landscape), '4/3', 'square' (1:1), 'none' (mengikuti tinggi parent)
    'class' => 'w-full h-full object-cover',
    'fallback' => null,
])

@php
    $resolvedSrc = $src;
    if ($src && !str_starts_with($src, 'http://') && !str_starts_with($src, 'https://') && !str_starts_with($src, 'data:') && !str_starts_with($src, '//')) {
        $resolvedSrc = asset(ltrim($src, '/'));
    }

    $fallbackUrl = $fallback ?: asset('images/asset-placeholder.svg');

    $aspectClasses = [
        'video'  => 'aspect-video',  // Standar Internasional Landscape 16:9
        '16/9'   => 'aspect-video',
        '4/3'    => 'aspect-[4/3]',
        'square' => 'aspect-square',
        'none'   => 'h-full',
    ];

    $aspectClass = $aspectClasses[$aspect] ?? ($aspect ? "aspect-{$aspect}" : 'aspect-video');
@endphp

<div {{ $attributes->class(["relative overflow-hidden w-full bg-base-300 {$aspectClass}"]) }}>
    {{-- Skeleton Shimmer Loading Indicator --}}
    <div class="skeleton absolute inset-0 w-full h-full"></div>

    {{-- Gambar (Mendukung Asset Lokal & URL Jaringan) --}}
    <img
        src="{{ $resolvedSrc }}"
        alt="{{ $alt }}"
        loading="lazy"
        onload="this.classList.remove('opacity-0'); if(this.previousElementSibling) this.previousElementSibling.remove();"
        onerror="if(this.dataset.triedFallback !== '1') { this.dataset.triedFallback = '1'; this.src='{{ $fallbackUrl }}'; } else { this.classList.remove('opacity-0'); if(this.previousElementSibling) this.previousElementSibling.remove(); }"
        class="opacity-0 transition-[opacity,scale] duration-700 ease-out {{ $class }}"
    />
</div>