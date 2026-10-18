<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- Tambahkan baris ini
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- Pastikan ini juga di-import untuk relasi hasMany
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetPhoto extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'photo_path',
        'condition_note',
        'uploaded_by',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
