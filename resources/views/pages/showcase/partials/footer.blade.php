{{-- FOOTER MESS TANJUNG LESUNG (SESUAI PALET WARNA UTAMA RESMI) --}}
<footer class="relative bg-gradient-to-b from-base-100 via-base-200 to-[#eaf0f8] text-base-content border-t border-base-300 pt-16 mt-auto">
    {{-- Wave Curve Accent di Bagian Atas (Menyatu dari section kamar bg-base-100) --}}
    <div class="absolute top-0 inset-x-0 -translate-y-full overflow-hidden leading-none pointer-events-none text-base-100">
        <svg class="w-full h-6 sm:h-10 fill-current" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M0,0 C150,90 350,-40 500,45 C650,130 900,10 1200,50 L1200,120 L0,120 Z"></path>
        </svg>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-8">
            
            {{-- KOLOM 1: BRANDING, DESKRIPSI & SOCIAL ICONS --}}
            <div class="lg:col-span-4 space-y-4">
                <a href="/" class="flex items-center gap-3 group">
                    <img 
                        src="{{ asset('images/tanjung-lesung.png') }}" 
                        alt="Logo Tanjung Lesung" 
                        class="w-12 h-12 object-contain group-hover:scale-105 transition-transform duration-300"
                        onerror="this.src='https://placehold.co/100x100?text=TL'"
                    />
                    <div>
                        <span class="font-extrabold text-xl tracking-tight text-primary block group-hover:text-primary/80 transition-colors">
                            Mess Tanjung Lesung
                        </span>
                        <span class="text-xs text-base-content/60 font-medium block">
                            Sistem Informasi & Aset Fasilitas
                        </span>
                    </div>
                </a>

                <p class="text-sm text-base-content/75 leading-relaxed pr-4">
                    Akomodasi terpadu dan sistem inventarisasi kondisi fisik aset untuk mendukung kenyamanan tinggal serta produktivitas harian peserta magang dan staf di kawasan Tanjung Lesung.
                </p>

                {{-- Social Icons Bulat Senada Warna Primary --}}
                <div class="flex items-center gap-2.5 pt-2">
                    {{-- YouTube / Video --}}
                    <a 
                        href="https://www.youtube.com/@TanjungLesungOfficial" 
                        target="_blank" 
                        rel="noopener noreferrer" 
                        class="w-10 h-10 rounded-full border border-primary/30 text-primary hover:bg-primary hover:text-white flex items-center justify-center transition-all duration-300 shadow-2xs hover:scale-105"
                        title="YouTube Tanjung Lesung"
                    >
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>

                    {{-- Instagram --}}
                    <a 
                        href="https://www.instagram.com/tanjunglesungid/" 
                        target="_blank" 
                        rel="noopener noreferrer" 
                        class="w-10 h-10 rounded-full border border-primary/30 text-primary hover:bg-primary hover:text-white flex items-center justify-center transition-all duration-300 shadow-2xs hover:scale-105"
                        title="Instagram @tanjunglesungid"
                    >
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>

                    {{-- WhatsApp --}}
                    <a 
                        href="https://wa.me/6283872003995" 
                        target="_blank" 
                        rel="noopener noreferrer" 
                        class="w-10 h-10 rounded-full border border-primary/30 text-primary hover:bg-primary hover:text-white flex items-center justify-center transition-all duration-300 shadow-2xs hover:scale-105"
                        title="Hubungi via WhatsApp (083872003995)"
                    >
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.312.045-.694.074-2.205-.551-1.933-.799-3.179-2.766-3.275-2.894-.096-.128-.781-1.037-.781-1.977 0-.94.492-1.401.667-1.593.175-.191.382-.239.51-.239.127 0 .255.002.366.007.119.006.277-.045.433.328.16.383.548 1.336.596 1.433.048.096.08.208.016.335-.064.128-.096.208-.191.319-.096.112-.201.25-.288.336-.096.096-.196.2-.085.39.112.191.498.823 1.069 1.331.737.655 1.358.858 1.55.953.191.096.303.08.415-.048.112-.128.479-.558.606-.75.128-.191.255-.16.431-.096.175.064 1.114.526 1.305.622.191.096.319.144.366.223.048.08.048.463-.096.868zM12 2C6.477 2 2 6.477 2 12c0 1.891.524 3.66 1.434 5.176L2 22l4.966-1.302A9.956 9.956 0 0 0 12 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm0 18.167c-1.696 0-3.273-.497-4.604-1.355l-.33-.213-2.949.774.787-2.875-.233-.37A8.136 8.136 0 0 1 3.833 12c0-4.503 3.664-8.167 8.167-8.167 4.503 0 8.167 3.664 8.167 8.167 0 4.503-3.664 8.167-8.167 8.167z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- KOLOM 2: EXPLORE / NAVIGASI --}}
            <div class="lg:col-span-2 space-y-4">
                <h4 class="font-extrabold text-base text-primary tracking-tight">Explore</h4>
                <ul class="space-y-2.5 text-sm text-base-content/80">
                    <li>
                        <a href="/" class="hover:text-primary transition-colors inline-block hover:translate-x-1 transition-transform">Beranda</a>
                    </li>
                    <li>
                        <a href="#fasilitas" class="hover:text-primary transition-colors inline-block hover:translate-x-1 transition-transform">Fasilitas Mess</a>
                    </li>
                    <li>
                        <a href="#kamar" class="hover:text-primary transition-colors inline-block hover:translate-x-1 transition-transform">Kamar & Area</a>
                    </li>
                    <li>
                        <a href="/inventory" class="hover:text-primary transition-colors inline-block hover:translate-x-1 transition-transform">Daftar Inventaris</a>
                    </li>
                    <li>
                        <a href="https://wa.me/6283872003995" target="_blank" rel="noopener noreferrer" class="hover:text-primary transition-colors inline-block hover:translate-x-1 transition-transform">Kontak Kami</a>
                    </li>
                </ul>
            </div>

            {{-- KOLOM 3: RESERVATION & CONTACT (DENGAN NOMOR 083872003995) --}}
            <div class="lg:col-span-4 space-y-4">
                <h4 class="font-extrabold text-base text-primary tracking-tight">Reservation Contact</h4>
                
                <div class="space-y-3 text-sm text-base-content/80">
                    <div>
                        <span class="font-bold text-slate-900 block text-sm">Kantor Pengelola Mess Tanjung Lesung</span>
                    </div>

                    {{-- Alamat --}}
                    <div class="flex items-start gap-2.5">
                        <x-icon name="o-map-pin" class="w-4 h-4 text-primary shrink-0 mt-0.5" />
                        <span class="leading-relaxed text-xs sm:text-sm">
                            Kawasan Ekonomi Khusus (KEK) Tanjung Lesung, Tanjung Jaya, Kec. Panimbang, Pandeglang, Banten 42264, Indonesia
                        </span>
                    </div>

                    {{-- Telepon --}}
                    <div class="flex items-center gap-2.5">
                        <x-icon name="o-phone" class="w-4 h-4 text-primary shrink-0" />
                        <a href="tel:083872003995" class="hover:text-primary font-semibold transition-colors">
                            +62 838-7200-3995
                        </a>
                    </div>

                    {{-- WhatsApp --}}
                    <div class="flex items-center gap-2.5">
                        <x-icon name="o-chat-bubble-oval-left-ellipsis" class="w-4 h-4 text-primary shrink-0" />
                        <a 
                            href="https://wa.me/6283872003995" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="hover:text-primary font-semibold transition-colors flex items-center gap-1.5"
                        >
                            +62 838 7200 3995
                            <span class="badge badge-xs badge-success text-white">Online</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- KOLOM 4: OFFICIAL SITES (TAUTAN RESMI) --}}
            <div class="lg:col-span-2 space-y-4">
                <h4 class="font-extrabold text-base text-primary tracking-tight">Official Sites</h4>
                <ul class="space-y-2.5 text-sm">
                    <li>
                        <a 
                            href="https://tanjunglesung.com" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="text-primary hover:text-secondary font-medium inline-flex items-center gap-1.5 hover:underline transition-colors"
                        >
                            <span>Tanjung Lesung</span>
                            <x-icon name="o-arrow-top-right-on-square" class="w-3.5 h-3.5" />
                        </a>
                    </li>
                    <li>
                        <a 
                            href="/inventory" 
                            class="text-primary hover:text-secondary font-medium inline-flex items-center gap-1.5 hover:underline transition-colors"
                        >
                            <span>Portal Inventaris</span>
                            <x-icon name="o-arrow-top-right-on-square" class="w-3.5 h-3.5" />
                        </a>
                    </li>
                    <li>
                        <a 
                            href="/login" 
                            class="text-primary hover:text-secondary font-medium inline-flex items-center gap-1.5 hover:underline transition-colors"
                        >
                            <span>Login Pengelola</span>
                            <x-icon name="o-arrow-top-right-on-square" class="w-3.5 h-3.5" />
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    {{-- BOTTOM BAR (WARNA UTAMA DEEP NAVY BLUE / BG-PRIMARY RESMI MESS TANJUNG LESUNG) --}}
    <div class="bg-primary text-primary-content py-4 text-xs font-medium border-t border-white/10 shadow-inner">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>© 2026 Mess Tanjung Lesung. All rights reserved.</p>
        </div>
    </div>
</footer>
