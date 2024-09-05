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
        Schema::create('service_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->nullable();
            $table->string('name')->nullable();
            $table->string('label')->nullable();
            $table->string('type')->default('text');
            $table->longText('options')->nullable();
            $table->text('value')->nullable();
            $table->text('hint')->nullable();
            $table->boolean('required')->default(false);
            $table->boolean('reserved')->default(false);
            $table->boolean('enabled')->default(false);
            $table->text('validation')->nullable();
            $table->json('service_field_data')->nullable();
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
        Schema::dropIfExists('service_fields');
    }
};
