<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            if (!Schema::hasColumn('contracts', 'tenant_signed_at')) {
                $table->timestamp('tenant_signed_at')->nullable()->after('contract_file');
            }
            if (!Schema::hasColumn('contracts', 'tenant_signed_name')) {
                $table->string('tenant_signed_name', 255)->nullable()->after('tenant_signed_at');
            }
        });

        // ✅ nếu contracts.status đang là ENUM chỉ có active/expired/cancelled
        // thì phải mở thêm "pending"
        DB::statement("ALTER TABLE contracts MODIFY status ENUM('pending','active','expired','cancelled') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        // rollback enum về như cũ (nếu bạn muốn)
        DB::statement("ALTER TABLE contracts MODIFY status ENUM('active','expired','cancelled') NOT NULL DEFAULT 'active'");

        Schema::table('contracts', function (Blueprint $table) {
            if (Schema::hasColumn('contracts', 'tenant_signed_name')) $table->dropColumn('tenant_signed_name');
            if (Schema::hasColumn('contracts', 'tenant_signed_at')) $table->dropColumn('tenant_signed_at');
        });
    }
};