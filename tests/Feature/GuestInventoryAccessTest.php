<?php

namespace Tests\Feature;

use Tests\TestCase;

class GuestInventoryAccessTest extends TestCase
{
    /**
     * Memastikan pengunjung (guest) dapat melihat halaman inventaris tanpa harus login.
     */
    public function test_guest_can_access_inventory_page(): void
    {
        $response = $this->get('/inventory');

        $response->assertStatus(200);
        $response->assertSee('Pengelolaan & Inventaris Aset');
        $response->assertSee('Peran: Viewer (Tanpa Perlu Login)');
    }

    /**
     * Memastikan route showcase dapat diakses oleh publik.
     */
    public function test_guest_can_access_showcase_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Daftar Inventaris');
    }
}
