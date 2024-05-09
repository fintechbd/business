<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('charge_break_downs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_stat_id')->nullable();
            $table->string('service_slug')->nullable();
            $table->double('charge_break_down_lower')->default(0);
            $table->double('charge_break_down_higher')->default(0);
            $table->string('charge_break_down_charge')->default(0);
            $table->string('charge_break_down_discount')->default(0);
            $table->string('charge_break_down_commission')->default(0);
            $table->boolean('enabled')->default(1);
            $table->foreignId('creator_id')->nullable();
            $table->foreignId('editor_id')->nullable();
            $table->foreignId('destroyer_id')->nullable();
            $table->foreignId('restorer_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('restored_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charge_break_downs');
    }
};
