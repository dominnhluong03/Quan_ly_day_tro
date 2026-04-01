<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('tenant_id');

            $table->date('start_date');
            $table->date('end_date');

            $table->double('price');
            $table->double('deposit');

            $table->text('service_note')->nullable(); // phí dịch vụ

            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('contracts');
    }
};