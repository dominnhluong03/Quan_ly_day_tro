<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {

            $table->timestamp('tenant_signed_at')
                  ->nullable()
                  ->after('contract_file');

            $table->string('tenant_signed_name', 120)
                  ->nullable()
                  ->after('tenant_signed_at');
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn([
                'tenant_signed_at',
                'tenant_signed_name'
            ]);
        });
    }
};