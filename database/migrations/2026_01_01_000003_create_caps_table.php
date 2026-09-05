<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('caps', function (Blueprint $table) {
            $table->id();
            $table->string('cap_ref')->unique();
            $table->text('finding');
            $table->text('action_plan');
            $table->string('state')->default('Lagos');
            $table->string('priority')->default('High'); // Low, Medium, High, Critical
            $table->string('status')->default('Open'); // Open, In Progress, Closed, Under Review
            $table->integer('progress_pct')->default(0);
            $table->date('due_date')->nullable();
            $table->foreignId('lead_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('complaint_id')->nullable()->constrained('complaints')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('cap_evidence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cap_id')->constrained('caps')->cascadeOnDelete();
            $table->string('file_name');
            $table->string('file_url');
            $table->text('notes')->nullable();
            $table->string('uploaded_by')->default('Compliance Officer');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cap_evidence');
        Schema::dropIfExists('caps');
    }
};
