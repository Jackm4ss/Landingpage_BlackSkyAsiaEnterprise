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
        Schema::table('users', function (Blueprint $table) {
            $table->string('registration_source', 40)->nullable()->after('avatar')->index();
            $table->string('registration_country_code', 2)->nullable()->after('registration_source')->index();
            $table->string('registration_referrer', 2048)->nullable()->after('registration_country_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'registration_source',
                'registration_country_code',
                'registration_referrer',
            ]);
        });
    }
};
