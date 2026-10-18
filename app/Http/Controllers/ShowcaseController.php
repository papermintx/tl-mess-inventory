<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Facility;
use App\Models\Room;
use Illuminate\View\View;

class ShowcaseController extends Controller
{
    /**
     * Tampilkan halaman utama showcase mess & inventaris aset.
     */
    public function index(): View
    {
        $facilities = Facility::all();
        $rooms = Room::allWithAssetCounts();

        $assets = Asset::query()
            ->with(['photos.uploader'])
            ->latest()
            ->get();

        $totalAssets = Asset::count();
        $totalCapacity = Room::totalCapacity();

        $countBagus = Asset::where('status', 'bagus')->count();
        $countPerbaikan = Asset::where('status', 'perlu_perbaikan')->count();
        $countRusak = Asset::where('status', 'rusak')->count();

        // Resolusi gambar background hero sunset / golden hour dari asset lokal
        $heroCandidates = [
            'images/sunset-hero.jpg',
            'images/sunset-hero.png',
            'images/hero-sunset.jpg',
            'images/hero-bg.jpg',
            'images/sunset.jpg',
        ];

        $heroBackgroundImage = null;
        foreach ($heroCandidates as $candidate) {
            if (file_exists(public_path($candidate))) {
                $heroBackgroundImage = asset($candidate);
                break;
            }
        }

        // Fallback jika belum ada file lokal di public/images/
        if (! $heroBackgroundImage) {
            $heroBackgroundImage = 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=2000&q=80';
        }

        return view('pages.showcase.showcase', compact(
            'facilities',
            'rooms',
            'assets',
            'totalAssets',
            'totalCapacity',
            'countBagus',
            'countPerbaikan',
            'countRusak',
            'heroBackgroundImage'
        ));
    }
}
