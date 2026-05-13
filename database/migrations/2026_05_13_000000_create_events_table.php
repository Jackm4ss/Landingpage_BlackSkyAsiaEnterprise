<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();
            $table->string('venue');
            $table->string('city')->index();
            $table->string('country_code', 2)->default('MY')->index();
            $table->string('genre', 80)->index();
            $table->date('start_date')->index();
            $table->date('end_date')->nullable()->index();
            $table->string('date_display')->nullable();
            $table->string('timezone', 64)->default('Asia/Kuala_Lumpur');
            $table->string('status', 40)->default('published')->index();
            $table->boolean('is_sold_out')->default(false)->index();
            $table->string('image_url', 2048)->nullable();
            $table->string('accent_color', 20)->default('#0EA5E9');
            $table->string('glow_color', 80)->nullable();
            $table->string('vendor_url', 2048)->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('og_image', 2048)->nullable();
            $table->timestamps();

            $table->index('title');
            $table->index('venue');
            $table->index(['status', 'start_date']);
            $table->index(['city', 'start_date']);
            $table->index(['genre', 'start_date']);
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE events ADD FULLTEXT events_search_fulltext (title, subtitle, venue, city, genre)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
