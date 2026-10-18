<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('room_code');       // Kode ruangan (misal: KMR-01, COMM-01) langsung dari data master
            $table->string('code')->unique();  // Kode aset: AST-TL-001
            $table->string('name');            // Contoh: AC Sharp 1 PK, Kasur Busa Inoac
            $table->string('category');        // Elektronik, Furniture, dll.
            $table->enum('status', ['bagus', 'perlu_perbaikan', 'rusak'])->default('bagus');
            $table->integer('quantity')->default(1);
            $table->text('notes')->nullable(); // Keterangan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
