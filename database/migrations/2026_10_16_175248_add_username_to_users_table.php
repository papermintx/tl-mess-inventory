<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
        });

        // Isi username untuk user yang sudah ada berdasarkan awalan email
        DB::table('users')->whereNull('username')->get()->each(function ($user) {
            $username = explode('@', $user->email)[0] ?? 'user'.$user->id;
            DB::table('users')->where('id', $user->id)->update(['username' => $username]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};
