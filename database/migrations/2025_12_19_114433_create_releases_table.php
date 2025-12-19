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
        Schema::create('releases', function (Blueprint $table) {
            $table->id();
            $table->boolean('uat_tested');
            $table->boolean('prod_ready');
            $table->text('test_results');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->boolean('downtime');
            $table->timestamp('downtime_from');
            $table->timestamp('downtime_to');
            $table->boolean('rollback_available');
            $table->text('remarks')->nullable();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('tested_by')->constrained('users');
            $table->enum('prod_status',['Success','Failed','Not Tested']);
            $table->enum('status',['Pending','Postponed','Rejected','Completed'])->default('Pending');
            $table->text('post_prod_issues')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('releases');
    }
};
