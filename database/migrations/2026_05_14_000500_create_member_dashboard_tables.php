<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'event_id']);
            $table->index(['user_id', 'created_at']);
        });

        Schema::create('synced_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('event_id')->nullable()->constrained()->nullOnDelete();
            $table->string('vendor', 80)->default('manual')->index();
            $table->string('external_order_id')->nullable()->index();
            $table->string('buyer_email')->index();
            $table->string('event_title')->nullable();
            $table->string('ticket_type')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('total_amount', 12, 2)->nullable();
            $table->string('currency', 3)->default('MYR');
            $table->string('status', 40)->default('confirmed')->index();
            $table->timestamp('purchased_at')->nullable()->index();
            $table->json('raw_payload')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'purchased_at']);
            $table->unique(['vendor', 'external_order_id']);
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['notifiable_type', 'notifiable_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('synced_transactions');
        Schema::dropIfExists('bookmarks');
    }
};
