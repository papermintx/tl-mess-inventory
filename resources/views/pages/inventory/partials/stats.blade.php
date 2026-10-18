{{-- SUMMARY STATISTIK ASET (DENGAN EFEK HOVER-LIFT SHOWCASE) --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <x-stat-card icon="o-cube" label="Total Aset" :value="$countTotal . ' Barang'" color="primary" />
    <x-stat-card icon="o-check-circle" label="Kondisi Bagus" :value="$countBagus" color="success" />
    <x-stat-card icon="o-wrench-screwdriver" label="Perlu Perbaikan" :value="$countPerbaikan" color="warning" />
    <x-stat-card icon="o-exclamation-triangle" label="Kondisi Rusak" :value="$countRusak" color="error" />
</div>
