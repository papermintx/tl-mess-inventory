{{-- FLOATING BAR TAMBAH ASET (DENGAN GRADASI THEME SHOWCASE) --}}
@auth
    @can('manage-assets')
        <div class="fixed bottom-8 right-8 z-40">
            <button 
                wire:click="create" 
                class="btn bg-gradient-to-r from-primary to-secondary text-white shadow-2xl shadow-primary/30 rounded-full px-6 gap-2 text-sm font-bold hover:scale-105 active:scale-95 transition-all border-none group"
            >
                <x-icon name="o-plus" class="w-5 h-5 stroke-[2.5] group-hover:rotate-90 transition-transform duration-300" />
                <span>Tambah Aset Baru</span>
            </button>
        </div>
    @endcan
@else
    <div class="fixed bottom-8 right-8 z-40">
        <a 
            href="/login" 
            class="btn bg-gradient-to-r from-primary to-secondary text-white shadow-2xl shadow-primary/30 rounded-full px-6 gap-2 text-sm font-bold hover:scale-105 active:scale-95 transition-all border-none group"
        >
            <x-icon name="o-arrow-right-on-rectangle" class="w-5 h-5 stroke-[2.5]" />
            <span>Login Pengelola</span>
        </a>
    </div>
@endauth
