{{-- BANNER INFORMASI PERAN & HAK AKSES INVENTARIS --}}
@if(auth()->user()?->isAdmin())
    <div class="alert bg-primary/5 border border-primary/20 shadow-sm rounded-2xl mb-6 flex items-start gap-3">
        <div class="w-10 h-10 rounded-xl bg-primary/20 text-primary flex items-center justify-center shrink-0 mt-0.5">
            <x-icon name="o-shield-check" class="w-5 h-5 text-primary" />
        </div>
        <div class="flex-1 text-xs">
            <div class="font-bold text-sm text-primary flex items-center gap-2">
                <span>Peran: Pengelola Mess (Administrator)</span>
                <span class="badge badge-primary badge-xs font-bold text-white">AKSES PENUH</span>
            </div>
            <p class="text-base-content/70 mt-1 leading-relaxed">
                Anda masuk sebagai <b>Pengelola (Admin)</b>. Anda memiliki hak akses penuh untuk menambah aset baru, mengubah data, memutakhirkan kondisi barang, mengunggah dokumentasi foto fisik lapangan, serta menghapus aset inventaris.
            </p>
        </div>
    </div>
@elseif(auth()->user()?->isViewer())
    <div class="alert bg-base-100 border border-base-300 shadow-sm rounded-2xl mb-6 flex items-start gap-3">
        <div class="w-10 h-10 rounded-xl bg-info/15 text-info flex items-center justify-center shrink-0 mt-0.5">
            <x-icon name="o-shield-check" class="w-5 h-5" />
        </div>
        <div class="flex-1 text-xs">
            <div class="font-bold text-sm text-base-content flex items-center gap-2">
                <span>Peran: Viewer / Peninjau</span>
                <span class="badge badge-neutral badge-xs font-bold">READ-ONLY</span>
            </div>
            <p class="text-base-content/70 mt-1 leading-relaxed">
                Anda berada dalam mode <b>Viewer</b>. Anda dapat memeriksa detail fisik barang, memantau sebaran aset, dan melihat dokumentasi foto. Pengelolaan data inventaris (tambah, edit, hapus) dikelola khusus oleh <b>Pengelola (Admin)</b>.
            </p>
        </div>
    </div>
@else
    <div class="alert bg-base-100 border border-base-300 shadow-sm rounded-2xl mb-6 flex items-start gap-3">
        <div class="w-10 h-10 rounded-xl bg-info/15 text-info flex items-center justify-center shrink-0 mt-0.5">
            <x-icon name="o-eye" class="w-5 h-5 text-info" />
        </div>
        <div class="flex-1 text-xs">
            <div class="font-bold text-sm text-base-content flex items-center justify-between flex-wrap gap-2">
                <div class="flex items-center gap-2">
                    <span>Peran: Viewer (Tanpa Perlu Login)</span>
                    <span class="badge badge-neutral badge-xs font-bold">DEFAULT / READ-ONLY</span>
                </div>
                <a href="/login" class="link link-primary font-semibold text-xs flex items-center gap-1 hover:underline">
                    <span>Login Khusus Pengelola</span>
                    <x-icon name="o-arrow-right" class="w-3.5 h-3.5" />
                </a>
            </div>
            <p class="text-base-content/70 mt-1 leading-relaxed">
                Semua pengunjung secara otomatis berada dalam peran <b>Viewer</b> dan <b>tidak perlu login</b>. Anda dapat langsung meninjau seluruh data inventaris, memantau kondisi fisik, dan melihat galeri foto aset. Hanya <b>Pengelola (Admin)</b> yang perlu login untuk mengelola daftar inventaris (tambah, ubah, atau hapus).
            </p>
        </div>
    </div>
@endif
