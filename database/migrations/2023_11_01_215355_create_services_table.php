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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_type_id')->nullable();
            $table->foreignId('service_vendor_id')->nullable();
            $table->string('service_name')->nullable();
            $table->string('service_slug')->nullable();
            $table->string('service_notification')->nullable()->default('no');
            $table->string('service_delay')->nullable()->default('no');
            $table->string('service_stat_policy')->nullable()->default('no');
            $table->string('service_serial')->nullable()->default(0);
            $table->integer('service_data')->nullable();
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
        Schema::dropIfExists('services');
    }
};
