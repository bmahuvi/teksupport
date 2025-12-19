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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('ticket_number')->unique();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->enum('priority',['Low','Medium','High','Urgent']);
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->enum('status',[
                'New',
                'Rejected',
                'Resolved',
                'Approved',
                'Accepted',
                'Cancelled',
                'Waiting Approval',
                'Waiting Release',
                'Closed',
            ]);
            $table->boolean('requires_approval')->default(false);
            $table->boolean('has_deadline')->default(false);
            $table->timestamp('deadline')->nullable();
            $table->boolean('to_main')->default(false);
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
