<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {

            // Nếu bạn chưa có 2 cột giá điện/nước thì thêm luôn (bỏ if nếu chắc chắn chưa có)
            if (!Schema::hasColumn('contracts', 'electric_price')) {
                $table->bigInteger('electric_price')->default(0)->after('deposit');
            }
            if (!Schema::hasColumn('contracts', 'water_price')) {
                $table->bigInteger('water_price')->default(0)->after('electric_price');
            }

            // Cột ký hợp đồng
            if (!Schema::hasColumn('contracts', 'tenant_signed_at')) {
                $table->timestamp('tenant_signed_at')->nullable()->after('contract_file');
            }
            if (!Schema::hasColumn('contracts', 'tenant_signed_name')) {
                $table->string('tenant_signed_name', 255)->nullable()->after('tenant_signed_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            if (Schema::hasColumn('contracts', 'tenant_signed_name')) $table->dropColumn('tenant_signed_name');
            if (Schema::hasColumn('contracts', 'tenant_signed_at')) $table->dropColumn('tenant_signed_at');

            // chỉ drop nếu bạn vừa thêm (nếu bạn đã có sẵn thì đừng drop)
            // $table->dropColumn(['electric_price','water_price']);
        });
    }
};