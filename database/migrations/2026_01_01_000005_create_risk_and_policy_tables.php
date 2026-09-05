<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risk_items', function (Blueprint $table) {
            $table->id();
            $table->string('risk_ref')->unique();
            $table->string('category');
            $table->string('title');
            $table->text('description');
            $table->integer('likelihood')->default(3); // 1-5
            $table->integer('impact')->default(3); // 1-5
            $table->integer('risk_score')->default(9); // likelihood * impact
            $table->string('status')->default('Active'); // Active, Mitigated, Closed
            $table->text('mitigation_strategy')->nullable();
            $table->string('owner')->default('Compliance Directorate');
            $table->timestamps();
        });

        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->string('policy_code')->unique();
            $table->string('title');
            $table->string('category');
            $table->string('version')->default('1.0');
            $table->date('effective_date');
            $table->string('status')->default('Active'); // Active, Archived, Draft
            $table->string('document_url')->nullable();
            $table->text('summary')->nullable();
            $table->timestamps();
        });

        Schema::create('policy_acknowledgements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->constrained('policies')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('acknowledged_at');
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('policy_acknowledgements');
        Schema::dropIfExists('policies');
        Schema::dropIfExists('risk_items');
    }
};
