@props(['icon', 'label', 'value', 'color' => 'primary'])

@php
    $iconClasses = [
        'primary' => 'bg-primary/10 text-primary group-hover:bg-primary',
        'success' => 'bg-success/10 text-success group-hover:bg-success',
        'warning' => 'bg-warning/10 text-warning group-hover:bg-warning',
        'error'   => 'bg-error/10 text-error group-hover:bg-error',
    ];

    $valueClasses = [
        'primary' => 'text-primary',
        'success' => 'text-success',
        'warning' => 'text-warning',
        'error'   => 'text-error',
    ];

    $iconClass  = $iconClasses[$color] ?? $iconClasses['primary'];
    $valueClass = $valueClasses[$color] ?? $valueClasses['primary'];
@endphp

<div class="card bg-base-100/90 backdrop-blur-sm border border-base-300 hover-lift shadow-sm p-4 group cursor-default">
    <div class="flex items-center gap-3">
        <div class="w-11 h-11 rounded-2xl flex items-center justify-center group-hover:text-white transition-all duration-300 {{ $iconClass }}">
            <x-icon :name="$icon" class="w-6 h-6" />
        </div>
        <div>
            <span class="text-xs text-base-content/60 block font-medium">{{ $label }}</span>
            <span class="text-xl font-extrabold {{ $valueClass }}">{{ $value }}</span>
        </div>
    </div>
</div>