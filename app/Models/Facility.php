<?php

namespace App\Models;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class Facility implements Arrayable, ArrayAccess
{
    /**
     * Data master fasilitas standar mess (in-memory, langsung diakses tanpa database).
     */
    public const array DATA = [
        [
            'name' => 'Kamar Tidur Berpendingin',
            'badge' => 'Kenyamanan',
            'icon' => 'o-home-modern',
            'description' => 'Dilengkapi kasur busa terawat, lemari pakaian individu, dan sirkulasi udara serta AC split yang dingin.',
            'cover_image' => '/images/facilities/kamar.jpeg',
            'tags' => ['AC Split', 'Kasur Busa', 'Lemari Pakaian'],
            'sort_order' => 1,
            'is_active' => true,
        ],
        [
            'name' => 'Ruang Bersama & Rekreasi',
            'badge' => 'Komunal',
            'icon' => 'o-user-group',
            'description' => 'Ruang santai komunal dengan sofa empuk dan koneksi internet stabil untuk briefing atau istirahat bersama.',
            'cover_image' => '/images/facilities/ruang-tamu.jpeg',
            'tags' => ['Sofa Bed', 'Area Briefing', 'Wi-Fi Cepat'],
            'sort_order' => 2,
            'is_active' => true,
        ],
        [
            'name' => 'Dapur Bersama & Area Makan',
            'badge' => 'Pantry',
            'icon' => 'o-fire',
            'description' => 'Fasilitas dapur bersama dengan peralatan memasak lengkap untuk kebutuhan makan harian peserta magang/pekerja.',
            'cover_image' => '/images/facilities/dapur.jpeg',
            'tags' => ['Peralatan Masak', 'Kompor Gas', 'Area Makan'],
            'sort_order' => 3,
            'is_active' => true,
        ],
    ];

    public string $name;

    public ?string $badge;

    public string $icon;

    public string $description;

    public string $cover_image;

    public array $tags = [];

    public int $sort_order = 1;

    public bool $is_active = true;

    public function __construct(array $attributes = [])
    {
        $this->name = (string) ($attributes['name'] ?? '');
        $this->badge = isset($attributes['badge']) ? (string) $attributes['badge'] : null;
        $this->icon = (string) ($attributes['icon'] ?? 'o-home-modern');
        $this->description = (string) ($attributes['description'] ?? '');
        $this->cover_image = (string) ($attributes['cover_image'] ?? '');
        $this->tags = (array) ($attributes['tags'] ?? []);
        $this->sort_order = (int) ($attributes['sort_order'] ?? 1);
        $this->is_active = (bool) ($attributes['is_active'] ?? true);
    }

    /**
     * Resolusi URL foto cover fasilitas (mendukung URL jaringan internet maupun path asset lokal).
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
     * Ambil semua data fasilitas aktif sebagai Collection dari instance Facility.
     *
     * @return Collection<int, Facility>
     */
    public static function all(): Collection
    {
        return collect(self::DATA)
            ->filter(fn (array $item) => $item['is_active'] ?? true)
            ->sortBy('sort_order')
            ->map(fn (array $item) => new self($item))
            ->values();
    }

    /**
     * Hitung total fasilitas aktif.
     */
    public static function count(): int
    {
        return self::all()->count();
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'badge' => $this->badge,
            'icon' => $this->icon,
            'description' => $this->description,
            'cover_image' => $this->cover_image,
            'cover_image_url' => $this->getCoverImageUrlAttribute(),
            'tags' => $this->tags,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
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
