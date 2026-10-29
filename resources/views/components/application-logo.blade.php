<div {{ $attributes->merge(['class' => 'flex items-center justify-center']) }}>
    <img 
        src="{{ asset('images/tanjung-lesung.png') }}" 
        alt="Logo Tanjung Lesung" 
        class="h-16 w-auto object-contain hover:scale-105 transition-transform duration-300" 
        onerror="this.src='https://placehold.co/100x100?text=TL'"
    />
</div>
