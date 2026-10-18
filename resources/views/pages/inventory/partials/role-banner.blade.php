{{-- BANNER TATA KELOLA OTORISASI PERAN (STANDAR TATA KELOLA PERBANKAN) --}}
@if(auth()->user()?->isViewer())
    <div class="alert bg-base-100 border border-base-300 shadow-sm rounded-2xl mb-6 flex items-start gap-3">
        <div class="w-10 h-10 rounded-xl bg-info/15 text-info flex items-center justify-center shrink-0 mt-0.5">
            <x-icon name="o-shield-check" class="w-5 h-5" />
        </div>
        <div class="flex-1 text-xs">
            <div class="font-bold text-sm text-base-content flex items-center gap-2">
                <span>Mode Akses Peninjau (Auditor / Staff Viewer)</span>
                <span class="badge badge-neutral badge-xs font-bold">READ-ONLY</span>
            </div>
            <p class="text-base-content/70 mt-1 leading-relaxed">
                Sesuai prinsip pemisahan wewenang (<i>Separation of Duties</i>) perbankan, akun Anda berfungsi untuk keperluan pemantauan, pemeriksaan kondisi lapangan, dan audit aset fisik. Operasi penambahan dan perubahan data hanya dapat dilakukan oleh <b>Administrator Pengelola</b>.
            </p>
        </div>
    </div>
@elseif(auth()->user()?->isAdmin())
    <div class="alert bg-primary/5 border border-primary/20 shadow-sm rounded-2xl mb-6 flex items-start gap-3">
        <div class="w-10 h-10 rounded-xl bg-primary/20 text-primary flex items-center justify-center shrink-0 mt-0.5">
            <x-icon name="o-shield-check" class="w-5 h-5 text-primary" />
        </div>
        <div class="flex-1 text-xs">
            <div class="font-bold text-sm text-primary flex items-center gap-2">
                <span>Otoritas Administrator Pengelola (Full Access)</span>
                <span class="badge badge-primary badge-xs font-bold text-white">ADMIN</span>
            </div>
            <p class="text-base-content/70 mt-1 leading-relaxed">
                Anda memegang hak akses penuh untuk mengelola master data aset, memutakhirkan kondisi fisik, mengunggah dokumentasi foto lapangan, dan menghapus aset inventaris. Setiap perubahan diaudit dan dicatat dalam log sistem.
            </p>
        </div>
    </div>
@else
    <div class="alert bg-base-100 border border-base-300 shadow-sm rounded-2xl mb-6 flex items-start gap-3">
        <div class="w-10 h-10 rounded-xl bg-warning/15 text-warning flex items-center justify-center shrink-0 mt-0.5">
            <x-icon name="o-eye" class="w-5 h-5" />
        </div>
        <div class="flex-1 text-xs">
            <div class="font-bold text-sm text-base-content flex items-center justify-between flex-wrap gap-2">
                <div class="flex items-center gap-2">
                    <span>Mode Pengunjung Publik (Guest Viewer)</span>
                    <span class="badge badge-warning badge-xs font-bold">READ-ONLY</span>
                </div>
                <a href="/login" class="link link-primary font-semibold text-xs flex items-center gap-1 hover:underline">
                    <span>Login Pengelola untuk Kelola Aset</span>
                    <x-icon name="o-arrow-right" class="w-3.5 h-3.5" />
                </a>
            </div>
            <p class="text-base-content/70 mt-1 leading-relaxed">
                Anda sedang melihat daftar inventaris dalam mode publik (hanya lihat). Anda dapat memeriksa detail dan galeri foto kondisi fisik. Untuk menambah, mengubah, atau menghapus data aset, silakan masuk sebagai <b>Administrator Pengelola</b>.
            </p>
        </div>
    </div>
@endif
