<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->time('start_time')->nullable()->after('start_date');
            $table->string('organizer_name')->nullable()->after('vendor_url');
            $table->string('organizer_url', 2048)->nullable()->after('organizer_name');
        });

        Schema::create('event_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('section_key', 80)->index();
            $table->string('title');
            $table->longText('content')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0)->index();
            $table->boolean('is_enabled')->default(true)->index();
            $table->timestamps();

            $table->index(['event_id', 'is_enabled', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_sections');

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'start_time',
                'organizer_name',
                'organizer_url',
            ]);
        });
    }
};
