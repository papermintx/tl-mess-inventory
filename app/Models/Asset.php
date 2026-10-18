<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_code',
        'code',
        'name',
        'category',
        'status',
        'quantity',
        'notes',
    ];

    /**
     * Relasi ke objek Room (in-memory data master tanpa tabel database).
     */
    public function room(): ?Room
    {
        return Room::findByCode($this->room_code);
    }

    /**
     * Accessor dinamis $asset->room
     */
    public function getRoomAttribute(): ?Room
    {
        return Room::findByCode($this->room_code);
    }

    /**
     * Kompatibilitas jika dipanggil via room_id
     */
    public function getRoomIdAttribute(): ?string
    {
        return $this->room_code;
    }

    public function setRoomIdAttribute(?string $value): void
    {
        $this->attributes['room_code'] = $value;
    }

    public function photos(): HasMany
    {
        return $this->hasMany(AssetPhoto::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'bagus' => 'badge-success',
            'perlu_perbaikan' => 'badge-warning',
            'rusak' => 'badge-error',
            default => 'badge-neutral',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'bagus' => 'Bagus',
            'perlu_perbaikan' => 'Perlu Perbaikan',
            'rusak' => 'Rusak',
            default => ucfirst(str_replace('_', ' ', (string) $this->status)),
        };
    }

    public function getHasPhotoAttribute(): bool
    {
        return $this->photos->isNotEmpty();
    }

    public function getPhotoUrlAttribute(): string
    {
        $photo = $this->photos->first();
        if ($photo && $photo->photo_path) {
            return asset('storage/'.$photo->photo_path);
        }

        return asset('images/asset-placeholder.svg');
    }

    public function getCategoryIconAttribute(): string
    {
        return match (strtolower((string) $this->category)) {
            'elektronik' => 'o-tv',
            'furniture' => 'o-home',
            'kebersihan' => 'o-sparkles',
            'keamanan' => 'o-shield-check',
            default => 'o-cube',
        };
    }
}
