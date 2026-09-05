<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('complaint_ref')->unique();
            $table->string('category');
            $table->string('severity')->default('Medium'); // Low, Medium, High, Critical
            $table->string('source')->default('Whistleblower');
            $table->string('state')->default('Lagos');
            $table->string('status')->default('Open'); // Open, In Progress, Closed
            $table->string('summary');
            $table->text('details')->nullable();
            $table->string('alleged_party')->nullable();
            $table->string('submitted_by')->default('Anonymous Whistleblower');
            $table->foreignId('assigned_to_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('incident_date')->nullable();
            $table->text('triage_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
