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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('ticket_ulid')->unique();
            $table->string('slug')->unique()->nullable();
            $table->string('ticket_number')->unique();
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('priority');
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('ticket_status_id')->constrained()->cascadeOnDelete();
            $table->boolean('requires_approval')->default(false);
            $table->boolean('has_deadline')->default(false);
            $table->timestamp('deadline')->nullable();
            $table->boolean('to_main')->default(false);
            $table->boolean('is_opened')->default(false);
            $table->foreignId('opened_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('opened_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
