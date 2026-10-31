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
            $table->boolean('is_first_admin')->default(false)->after('role');
            $table->string('admin_request_status', 20)->nullable()->after('is_first_admin');
            $table->text('admin_request_reason')->nullable()->after('admin_request_status');
            $table->timestamp('admin_requested_at')->nullable()->after('admin_request_reason');
            $table->timestamp('admin_action_at')->nullable()->after('admin_requested_at');
            $table->foreignId('admin_action_by')->nullable()->after('admin_action_at')->constrained('users')->nullOnDelete();
        });

        // Set admin pertama jika sudah ada user dengan role admin
        $firstAdminId = DB::table('users')
            ->where('role', 'admin')
            ->orWhere('username', 'admin')
            ->orderBy('id', 'asc')
            ->value('id');

        if ($firstAdminId) {
            DB::table('users')->where('id', $firstAdminId)->update([
                'is_first_admin' => true,
                'admin_request_status' => 'approved',
                'admin_action_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['admin_action_by']);
            $table->dropColumn([
                'is_first_admin',
                'admin_request_status',
                'admin_request_reason',
                'admin_requested_at',
                'admin_action_at',
                'admin_action_by',
            ]);
        });
    }
};
