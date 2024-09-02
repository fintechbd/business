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
        Schema::create('service_settings', function (Blueprint $table) {
            $table->id();
            $table->string('service_setting_type')->nullable();
            $table->string('service_setting_name')->nullable();
            $table->string('service_setting_field_name')->nullable();
            $table->string('service_setting_type_field')->nullable();
            $table->string('service_setting_feature')->nullable();
            $table->string('service_setting_rule')->nullable();
            $table->string('service_setting_value')->nullable();
            $table->boolean('enabled')->nullable();
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
        Schema::dropIfExists('service_settings');
    }
};
