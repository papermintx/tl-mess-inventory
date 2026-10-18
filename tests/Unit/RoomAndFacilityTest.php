<?php

namespace Tests\Unit;

use App\Models\Asset;
use App\Models\Facility;
use App\Models\Room;
use PHPUnit\Framework\TestCase;

class RoomAndFacilityTest extends TestCase
{
    public function test_room_all_returns_specified_rooms(): void
    {
        $rooms = Room::all();
        $this->assertCount(7, $rooms);

        $expectedCodes = [
            'KMR-01',
            'KMR-03',
            'KMR-06',
            'COMM-01',
            'KIT-01',
            'BTH-01',
            'BTH-02',
        ];

        $this->assertSame($expectedCodes, Room::codes());
    }

    public function test_room_find_by_code(): void
    {
        $room = Room::find('KMR-01');
        $this->assertNotNull($room);
        $this->assertSame('Kamar Cowok 01 (Area Depan)', $room->name);
        $this->assertSame(1, $room->capacity);
        $this->assertSame('/images/kamar-depan.jpeg', $room->cover_image);

        $comm = Room::find('COMM-01');
        $this->assertNotNull($comm);
        $this->assertSame('Ruang Tengah & Bersama', $comm->name);
        $this->assertSame(10, $comm->capacity);

        $this->assertNull(Room::find('NON-EXISTENT'));
    }

    public function test_room_total_capacity(): void
    {
        // 1 + 1 + 1 + 10 + 1 + 1 + 1 = 16
        $this->assertSame(16, Room::totalCapacity());
    }

    public function test_facility_all_returns_active_facilities(): void
    {
        $facilities = Facility::all();
        $this->assertCount(3, $facilities);

        $names = $facilities->pluck('name')->all();
        $this->assertContains('Kamar Tidur Berpendingin', $names);
        $this->assertContains('Ruang Bersama & Rekreasi', $names);
        $this->assertContains('Dapur Bersama & Area Makan', $names);
    }

    public function test_asset_resolves_in_memory_room(): void
    {
        $asset = new Asset([
            'room_code' => 'KMR-03',
            'code' => 'AST-KMR03-001',
            'name' => 'Kasur Busa Inoac',
        ]);

        $this->assertNotNull($asset->room);
        $this->assertSame('Kamar Cowok 03 (Area Tengah)', $asset->room->name);
        $this->assertSame('KMR-03', $asset->room_id);
    }
}
