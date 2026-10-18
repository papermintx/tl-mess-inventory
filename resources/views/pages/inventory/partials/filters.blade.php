{{-- FILTER & PENCARIAN (CARD GLASSMORPHISM) --}}
<div class="card bg-base-100/90 backdrop-blur-sm mb-6 shadow-sm border border-base-300 p-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <x-input 
            placeholder="Cari kode atau nama barang..." 
            wire:model.live.debounce="search" 
            icon="o-magnifying-glass" 
            clearable 
        />

        <x-select 
            placeholder="Semua Ruangan (1 Lantai)" 
            wire:model.live="filterRoom" 
            :options="$rooms" 
            option-value="code" 
            option-label="name" 
            icon="o-map-pin" 
            clearable 
        />

        <x-select 
            placeholder="Semua Kondisi Fisik" 
            wire:model.live="filterStatus" 
            :options="[
                ['id' => 'bagus', 'name' => 'Bagus'],
                ['id' => 'perlu_perbaikan', 'name' => 'Perlu Perbaikan'],
                ['id' => 'rusak', 'name' => 'Rusak']
            ]" 
            icon="o-adjustments-horizontal" 
            clearable 
        />
    </div>
</div>
