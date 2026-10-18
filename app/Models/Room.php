<?php

namespace App\Models;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class Room implements Arrayable, ArrayAccess
{
    /**
     * Data master ruangan mess Tanjung Lesung (in-memory, langsung diakses tanpa database).
     */
    public const array DATA = [
        [
            'code' => 'KMR-01',
            'name' => 'Kamar Cowok 01 (Area Depan)',
            'capacity' => 1,
            'description' => 'Kamar tidur single di sisi depan dengan sirkulasi udara alami dan pencahayaan optimal.',
            'cover_image' => '/images/kamar-depan.jpeg',
        ],
        [
            'code' => 'KMR-03',
            'name' => 'Kamar Cowok 03 (Area Tengah)',
            'capacity' => 1,
            'description' => 'Kamar tidur single dilengkapi kasur busa terawat, meja kerja, dan pendingin ruangan AC split.',
            'cover_image' => '/images/kamar-tengah.jpeg',
        ],
        [
            'code' => 'KMR-06',
            'name' => 'Kamar Cowok 06 (Area Belakang)',
            'capacity' => 1,
            'description' => 'Kamar tidur single posisi tengah lorong dengan suasana tenang untuk istirahat malam.',
            'cover_image' => '/images/kamar-belakang.jpeg',
        ],
        [
            'code' => 'COMM-01',
            'name' => 'Ruang Tengah & Bersama',
            'capacity' => 10,
            'description' => 'Area kumpul bersama dan santai dilengkapi sofa, dan meja diskusi.',
            'cover_image' => '/images/kumpul.jpeg',
        ],
        [
            'code' => 'KIT-01',
            'name' => 'Dapur & Area Makan',
            'capacity' => 1,
            'description' => 'Fasilitas dapur bersama untuk memasak harian peserta magang/pekerja.',
            'cover_image' => '/images/masak-masak.jpeg',
        ],
        [
            'code' => 'BTH-01',
            'name' => 'Kamar Mandi & Toilet',
            'capacity' => 1,
            'description' => 'Fasilitas kamar mandi dan toilet bersama untuk peserta magang/pekerja.',
            'cover_image' => '/images/mandi-depan.jpeg',
        ],
        [
            'code' => 'BTH-02',
            'name' => 'Kamar Mandi & Toilet',
            'capacity' => 1,
            'description' => 'Fasilitas kamar mandi dan toilet bersama untuk peserta magang/pekerja.',
            'cover_image' => '/images/mandi-belakang.jpeg',
        ],
    ];

    public string $id;

    public string $code;

    public string $name;

    public int $capacity;

    public string $description;

    public string $cover_image;

    public int $assets_count = 0;

    public function __construct(array $attributes = [])
    {
        $this->code = (string) ($attributes['code'] ?? '');
        $this->id = $this->code; // id identik dengan code untuk kemudahan komponen UI
        $this->name = (string) ($attributes['name'] ?? '');
        $this->capacity = (int) ($attributes['capacity'] ?? 0);
        $this->description = (string) ($attributes['description'] ?? '');
        $this->cover_image = (string) ($attributes['cover_image'] ?? '');
        $this->assets_count = (int) ($attributes['assets_count'] ?? 0);
    }

    /**
     * Resolusi URL foto cover ruangan (mendukung URL jaringan internet maupun path asset lokal).
     */
    public function getCoverImageUrlAttribute(): string
    {
        if (! $this->cover_image) {
            return asset('images/asset-placeholder.svg');
        }

        if (str_starts_with($this->cover_image, 'http://') || str_starts_with($this->cover_image, 'https://') || str_starts_with($this->cover_image, '//')) {
            return $this->cover_image;
        }

        return asset(ltrim($this->cover_image, '/'));
    }

    public function __get(string $name): mixed
    {
        if ($name === 'cover_image_url') {
            return $this->getCoverImageUrlAttribute();
        }

        return $this->$name ?? null;
    }

    /**
     * Ambil semua data ruangan sebagai Collection dari instance Room.
     *
     * @return Collection<int, Room>
     */
    public static function all(): Collection
    {
        return collect(self::DATA)->map(fn (array $item) => new self($item));
    }

    /**
     * Ambil seluruh kode ruangan yang valid.
     *
     * @return array<int, string>
     */
    public static function codes(): array
    {
        return array_column(self::DATA, 'code');
    }

    /**
     * Cari satu ruangan berdasarkan kode / id.
     */
    public static function find(?string $code): ?self
    {
        if (! $code) {
            return null;
        }

        $found = collect(self::DATA)->first(fn ($item) => $item['code'] === $code);

        return $found ? new self($found) : null;
    }

    public static function findByCode(?string $code): ?self
    {
        return self::find($code);
    }

    /**
     * Ambil semua data ruangan lengkap dengan penghitungan jumlah aset fisik terkait.
     *
     * @return Collection<int, Room>
     */
    public static function allWithAssetCounts(): Collection
    {
        $counts = Asset::query()
            ->selectRaw('room_code, count(*) as count')
            ->groupBy('room_code')
            ->pluck('count', 'room_code');

        return self::all()->map(function (Room $room) use ($counts) {
            $room->assets_count = (int) ($counts[$room->code] ?? 0);

            return $room;
        });
    }

    /**
     * Hitung total kapasitas seluruh ruangan mess.
     */
    public static function totalCapacity(): int
    {
        return (int) collect(self::DATA)->sum('capacity');
    }

    /**
     * Hitung total unit ruangan.
     */
    public static function count(): int
    {
        return count(self::DATA);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'capacity' => $this->capacity,
            'description' => $this->description,
            'cover_image' => $this->cover_image,
            'cover_image_url' => $this->getCoverImageUrlAttribute(),
            'assets_count' => $this->assets_count,
        ];
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->$offset) || $offset === 'cover_image_url';
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->__get((string) $offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->$offset = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->$offset);
    }
}
