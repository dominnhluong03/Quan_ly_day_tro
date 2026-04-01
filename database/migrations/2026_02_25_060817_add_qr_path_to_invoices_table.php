<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // lưu đường dẫn ảnh QR cố định của chủ trọ (VD: qr/owner.png)
            $table->string('qr_path')->nullable()->after('invoice_file');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('qr_path');
        });
    }
};