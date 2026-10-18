{{-- STATISTIK SINGKAT --}}
<section class="py-10 bg-base-100 border-y border-base-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="p-6 rounded-2xl bg-base-200/60 hover-lift border border-base-300/50">
                <div class="text-3xl font-extrabold text-primary mb-1">{{ $rooms->count() }}</div>
                <div class="text-xs font-semibold text-base-content/60 uppercase tracking-wider">Ruangan / Kamar</div>
            </div>
            <div class="p-6 rounded-2xl bg-base-200/60 hover-lift border border-base-300/50">
                <div class="text-3xl font-extrabold text-secondary mb-1">{{ $totalAssets }}</div>
                <div class="text-xs font-semibold text-base-content/60 uppercase tracking-wider">Aset Terdata</div>
            </div>
            <div class="p-6 rounded-2xl bg-base-200/60 hover-lift border border-base-300/50">
                <div class="text-3xl font-extrabold text-primary mb-1">{{ $totalCapacity }}+</div>
                <div class="text-xs font-semibold text-base-content/60 uppercase tracking-wider">Kapasitas Orang</div>
            </div>
            <div class="p-6 rounded-2xl bg-base-200/60 hover-lift border border-base-300/50">
                <div class="text-3xl font-extrabold text-success mb-1">{{ $totalAssets > 0 ? round(($countBagus / $totalAssets) * 100) : 100 }}%</div>
                <div class="text-xs font-semibold text-base-content/60 uppercase tracking-wider">Kondisi Siap Pakai</div>
            </div>
        </div>
    </div>
</section>

