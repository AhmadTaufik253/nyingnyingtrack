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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                ->constrained()
                ->cascadeOnDelete();

            // Identity
            $table->string('imei')->unique();
            $table->string('name')->nullable();
            $table->string('model')->nullable();
            $table->string('protocol')->default('teltonika');
            $table->string('firmware')->nullable();

            // SIM
            $table->string('sim_number')->nullable();
            $table->string('phone_number')->nullable();

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_online')->default(false);

            // Last Connection
            $table->ipAddress('last_ip')->nullable();
            $table->timestamp('last_seen')->nullable();

            // Last Position (cache)
            $table->decimal('last_latitude', 10, 7)->nullable();
            $table->decimal('last_longitude', 10, 7)->nullable();
            $table->integer('last_altitude')->nullable();
            $table->float('last_speed')->nullable();
            $table->integer('last_course')->nullable();
            $table->integer('last_satellites')->nullable();

            $table->timestamp('last_position_time')->nullable();

            $table->timestamps();

            $table->index('imei');
            $table->index('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
