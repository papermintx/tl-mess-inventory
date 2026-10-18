<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetPhoto;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Default (Admin & Viewer)
        $admin = User::create([
            'name' => 'Admin Pengelola Mess',
            'username' => 'admin',
            'email' => 'admin@tanjunglesung.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Staff / Peserta Magang (Viewer)',
            'username' => 'viewer',
            'email' => 'viewer@tanjunglesung.com',
            'password' => Hash::make('password'),
            'role' => 'viewer',
        ]);

        // 2. Data Mock Aset Inventaris (terhubung ke kode ruangan in-memory)
        // KMR-01: Kamar Cowok 01 (Area Depan)
        $acDaikinKmr01 = Asset::create([
            'room_code' => 'KMR-01',
            'code' => 'AST-KMR01-001',
            'name' => 'AC Split Daikin 1 PK',
            'category' => 'Elektronik',
            'status' => 'bagus',
            'quantity' => 1,
            'notes' => 'Dingin normal, servis berkala terakhir 1 bulan lalu.',
        ]);

        $kasurKmr01 = Asset::create([
            'room_code' => 'KMR-01',
            'code' => 'AST-KMR01-002',
            'name' => 'Kasur Busa Inoac Single Bed',
            'category' => 'Furniture',
            'status' => 'bagus',
            'quantity' => 1,
            'notes' => 'Kondisi spon padat dan nyaman untuk 1 orang.',
        ]);

        // KMR-03: Kamar Cowok 03 (Area Tengah)
        $kasurKmr03 = Asset::create([
            'room_code' => 'KMR-03',
            'code' => 'AST-KMR03-001',
            'name' => 'Kasur Busa Inoac Single Bed',
            'category' => 'Furniture',
            'status' => 'bagus',
            'quantity' => 1,
            'notes' => 'Kasur single dalam kondisi siap pakai.',
        ]);

        $acSharpKmr03 = Asset::create([
            'room_code' => 'KMR-03',
            'code' => 'AST-KMR03-002',
            'name' => 'AC Split Sharp 1/2 PK',
            'category' => 'Elektronik',
            'status' => 'bagus',
            'quantity' => 1,
            'notes' => 'Pendingin ruangan dingin stabil dan hening.',
        ]);

        Asset::create([
            'room_code' => 'KMR-03',
            'code' => 'AST-KMR03-003',
            'name' => 'Meja Kerja & Kursi Belajar',
            'category' => 'Furniture',
            'status' => 'perlu_perbaikan',
            'quantity' => 1,
            'notes' => 'Kaki meja sedikit goyang perlu dikencangkan bautnya.',
        ]);

        // KMR-06: Kamar Cowok 06 (Area Belakang)
        Asset::create([
            'room_code' => 'KMR-06',
            'code' => 'AST-KMR06-001',
            'name' => 'Kasur Busa Inoac Single Bed',
            'category' => 'Furniture',
            'status' => 'bagus',
            'quantity' => 1,
            'notes' => 'Kasur single kondisi terawat rapi.',
        ]);

        Asset::create([
            'room_code' => 'KMR-06',
            'code' => 'AST-KMR06-002',
            'name' => 'Lemari Pakaian 2 Pintu',
            'category' => 'Furniture',
            'status' => 'bagus',
            'quantity' => 1,
            'notes' => 'Kunci lemari lengkap dan engsel berfungsi baik.',
        ]);

        // COMM-01: Ruang Tengah & Bersama
        $smartTvComm = Asset::create([
            'room_code' => 'COMM-01',
            'code' => 'AST-COMM-001',
            'name' => 'Smart TV LG 43 Inch',
            'category' => 'Elektronik',
            'status' => 'bagus',
            'quantity' => 1,
            'notes' => 'Layar jernih, remote TV lengkap ada di rak.',
        ]);

        $sofaComm = Asset::create([
            'room_code' => 'COMM-01',
            'code' => 'AST-COMM-002',
            'name' => 'Sofa Bed 3 Seater',
            'category' => 'Furniture',
            'status' => 'bagus',
            'quantity' => 1,
            'notes' => 'Kain sofa bersih, tidak ada sobekan.',
        ]);

        Asset::create([
            'room_code' => 'COMM-01',
            'code' => 'AST-COMM-003',
            'name' => 'Kipas Angin Gantung Panasonic',
            'category' => 'Elektronik',
            'status' => 'bagus',
            'quantity' => 2,
            'notes' => 'Putaran kencang dan merata ke seluruh ruangan kumpul.',
        ]);

        // KIT-01: Dapur & Area Makan
        $kulkasKit = Asset::create([
            'room_code' => 'KIT-01',
            'code' => 'AST-KIT-001',
            'name' => 'Kulkas Sharp 2 Pintu',
            'category' => 'Elektronik',
            'status' => 'bagus',
            'quantity' => 1,
            'notes' => 'Freezer membeku sempurna, kompartemen bawah dingin normal.',
        ]);

        $dispenserKit = Asset::create([
            'room_code' => 'KIT-01',
            'code' => 'AST-KIT-002',
            'name' => 'Dispenser Air Galon Miyako',
            'category' => 'Elektronik',
            'status' => 'rusak',
            'quantity' => 1,
            'notes' => 'Fungsi pemanas mati (hanya air normal yang mengalir), perlu ganti elemen pemanas.',
        ]);

        Asset::create([
            'room_code' => 'KIT-01',
            'code' => 'AST-KIT-003',
            'name' => 'Kompor Gas Rinnai 2 Tungku',
            'category' => 'Elektronik',
            'status' => 'bagus',
            'quantity' => 1,
            'notes' => 'Api biru merata, regulator gas aman.',
        ]);

        // BTH-01: Kamar Mandi & Toilet Depan
        $waterHeaterBth = Asset::create([
            'room_code' => 'BTH-01',
            'code' => 'AST-BTH01-001',
            'name' => 'Water Heater Listrik Ariston 15L',
            'category' => 'Elektronik',
            'status' => 'bagus',
            'quantity' => 1,
            'notes' => 'Air panas stabil, indikator pengaman ELCB aktif.',
        ]);

        Asset::create([
            'room_code' => 'BTH-01',
            'code' => 'AST-BTH01-002',
            'name' => 'Cermin Wastafel & Rak Kaca',
            'category' => 'Furniture',
            'status' => 'bagus',
            'quantity' => 1,
            'notes' => 'Cermin bersih tanpa retakan.',
        ]);

        // BTH-02: Kamar Mandi & Toilet Belakang
        $exhaustFanBth = Asset::create([
            'room_code' => 'BTH-02',
            'code' => 'AST-BTH02-001',
            'name' => 'Exhaust Fan Dinding Maspion',
            'category' => 'Elektronik',
            'status' => 'bagus',
            'quantity' => 1,
            'notes' => 'Sirkulasi udara lancar membuang kelembapan.',
        ]);

        Asset::create([
            'room_code' => 'BTH-02',
            'code' => 'AST-BTH02-002',
            'name' => 'Rak Perlengkapan Mandi Stainless Steel',
            'category' => 'Furniture',
            'status' => 'bagus',
            'quantity' => 1,
            'notes' => 'Bahan tahan karat, terpasang kokoh pada dinding.',
        ]);

        // 3. Dokumentasi Foto Fisik Mock Aset
        AssetPhoto::create([
            'asset_id' => $acDaikinKmr01->id,
            'photo_path' => 'assets/ac-daikin-kmr01.jpg',
            'condition_note' => 'Kondisi fisik unit AC Daikin bersih, hembusan dingin normal, dan tidak berisik.',
            'uploaded_by' => $admin->id,
        ]);

        AssetPhoto::create([
            'asset_id' => $kasurKmr01->id,
            'photo_path' => 'assets/kasur-kmr01.jpg',
            'condition_note' => 'Kasur busa single bed rapi dengan spon padat dan nyaman.',
            'uploaded_by' => $admin->id,
        ]);

        AssetPhoto::create([
            'asset_id' => $acSharpKmr03->id,
            'photo_path' => 'assets/ac-sharp-kmr03.jpg',
            'condition_note' => 'Unit AC Sharp terpasang kokoh di dinding kamar 03 area tengah.',
            'uploaded_by' => $admin->id,
        ]);

        AssetPhoto::create([
            'asset_id' => $smartTvComm->id,
            'photo_path' => 'assets/smart-tv-comm01.jpg',
            'condition_note' => 'Layar Smart TV LG jernih dengan remote TV dan kabel power lengkap.',
            'uploaded_by' => $admin->id,
        ]);

        AssetPhoto::create([
            'asset_id' => $sofaComm->id,
            'photo_path' => 'assets/sofa-bed-comm01.jpg',
            'condition_note' => 'Sofa bed 3 seater bersih di ruang kumpul bersama tanpa ada sobekan.',
            'uploaded_by' => $admin->id,
        ]);

        AssetPhoto::create([
            'asset_id' => $kulkasKit->id,
            'photo_path' => 'assets/kulkas-kit01.jpg',
            'condition_note' => 'Kulkas 2 pintu bersih, freezer membeku sempurna, dan higienis.',
            'uploaded_by' => $admin->id,
        ]);

        AssetPhoto::create([
            'asset_id' => $dispenserKit->id,
            'photo_path' => 'assets/dispenser-kit01.jpg',
            'condition_note' => 'Kerusakan fisik switch pemanas air dispenser galon, butuh penggantian suku cadang.',
            'uploaded_by' => $admin->id,
        ]);

        AssetPhoto::create([
            'asset_id' => $waterHeaterBth->id,
            'photo_path' => 'assets/water-heater-bth01.jpg',
            'condition_note' => 'Water heater listrik berfungsi baik dengan pengaman stop kontak kering.',
            'uploaded_by' => $admin->id,
        ]);

        AssetPhoto::create([
            'asset_id' => $exhaustFanBth->id,
            'photo_path' => 'assets/exhaust-fan-bth02.jpg',
            'condition_note' => 'Exhaust fan dinding berputar lancar untuk sirkulasi udara kamar mandi belakang.',
            'uploaded_by' => $admin->id,
        ]);
    }
}
