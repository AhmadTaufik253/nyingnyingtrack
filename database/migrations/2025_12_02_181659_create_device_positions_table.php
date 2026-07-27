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
        Schema::create('device_positions', function (Blueprint $table) {

        $table->id();

        $table->foreignId('device_id')
            ->constrained()
            ->cascadeOnDelete();

        // GPS
        $table->decimal('latitude',10,7);
        $table->decimal('longitude',10,7);

        $table->integer('altitude')->nullable();
        $table->integer('angle')->nullable();

        $table->float('speed')->default(0);

        $table->unsignedTinyInteger('satellites')->default(0);

        // AVL
        $table->unsignedTinyInteger('priority')->nullable();
        $table->unsignedTinyInteger('event_id')->nullable();

        // GPS Time
        $table->timestamp('gps_time');

        // Semua IO
        $table->json('attributes')->nullable();

        $table->timestamps();

        $table->index(['device_id','gps_time']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_positions');
    }
};
