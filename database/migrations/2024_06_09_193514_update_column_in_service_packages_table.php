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
        Schema::table('service_packages', function (Blueprint $table) {
            $table->foreignId('service_id')->after('id');
            $table->string('code')->after('service_id');
            $table->float('rate')->after('code');
            $table->boolean('enabled')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_packages', function (Blueprint $table) {
            $table->dropColumn('service_id');
            $table->dropColumn('code');
            $table->dropColumn('rate');
        });
    }
};
