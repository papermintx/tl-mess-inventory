<?php

namespace Tests\Unit;

use App\Models\Asset;
use PHPUnit\Framework\TestCase;

class AssetModelTest extends TestCase
{
    public function test_asset_status_color_attribute(): void
    {
        $assetBagus = new Asset(['status' => 'bagus']);
        $this->assertSame('badge-success', $assetBagus->status_color);

        $assetPerbaikan = new Asset(['status' => 'perlu_perbaikan']);
        $this->assertSame('badge-warning', $assetPerbaikan->status_color);

        $assetRusak = new Asset(['status' => 'rusak']);
        $this->assertSame('badge-error', $assetRusak->status_color);
    }

    public function test_asset_status_label_attribute(): void
    {
        $assetBagus = new Asset(['status' => 'bagus']);
        $this->assertSame('Bagus', $assetBagus->status_label);

        $assetPerbaikan = new Asset(['status' => 'perlu_perbaikan']);
        $this->assertSame('Perlu Perbaikan', $assetPerbaikan->status_label);

        $assetRusak = new Asset(['status' => 'rusak']);
        $this->assertSame('Rusak', $assetRusak->status_label);
    }

    public function test_asset_category_icon_attribute(): void
    {
        $assetElektronik = new Asset(['category' => 'Elektronik']);
        $this->assertSame('o-tv', $assetElektronik->category_icon);

        $assetFurniture = new Asset(['category' => 'Furniture']);
        $this->assertSame('o-home', $assetFurniture->category_icon);

        $assetLain = new Asset(['category' => 'Lainnya']);
        $this->assertSame('o-cube', $assetLain->category_icon);
    }
}
