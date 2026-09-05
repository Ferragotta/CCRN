<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investigation_cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_ref')->unique();
            $table->foreignId('complaint_id')->nullable()->constrained('complaints')->nullOnDelete();
            $table->string('title');
            $table->string('lead_investigator');
            $table->string('status')->default('Active Investigation');
            $table->string('severity')->default('Critical');
            $table->text('findings_summary')->nullable();
            $table->date('closure_date')->nullable();
            $table->timestamps();
        });

        Schema::create('rca_findings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('investigation_cases')->cascadeOnDelete();
            $table->string('cause_type');
            $table->text('description');
            $table->text('contributing_factors')->nullable();
            $table->timestamps();
        });

        Schema::create('control_deviations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('investigation_cases')->cascadeOnDelete();
            $table->string('control_standard');
            $table->text('observed_deviation');
            $table->string('severity')->default('High');
            $table->timestamps();
        });

        Schema::create('evidence_custodies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('investigation_cases')->cascadeOnDelete();
            $table->string('item_description');
            $table->string('collected_by');
            $table->timestamp('collected_at');
            $table->string('custody_location');
            $table->string('file_hash')->nullable();
            $table->timestamps();
        });

        Schema::create('pdps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('staff_name');
            $table->string('department');
            $table->string('state');
            $table->string('review_period')->default('2026 Q1/Q2');
            $table->integer('objective_score')->default(52); // /60
            $table->integer('behaviour_score')->default(34); // /40
            $table->integer('innovation_score')->default(42); // /50
            $table->integer('total_score')->default(128); // /150
            $table->string('status')->default('Supervisor Approved');
            $table->text('supervisor_feedback')->nullable();
            $table->timestamps();
        });

        Schema::create('training_modules', function (Blueprint $table) {
            $table->id();
            $table->string('module_code')->unique();
            $table->string('title');
            $table->string('category');
            $table->integer('duration_hours')->default(2);
            $table->string('target_audience')->default('All Staff');
            $table->boolean('mandatory')->default(true);
            $table->string('status')->default('Active');
            $table->timestamps();
        });

        Schema::create('training_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('training_modules')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('completed_at');
            $table->integer('score')->default(100);
            $table->string('certificate_url')->nullable();
            $table->timestamps();
        });

        Schema::create('state_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->string('cluster');
            $table->string('lead_name');
            $table->integer('staff_count')->default(45);
            $table->integer('compliance_score')->default(85);
            $table->string('status')->default('Fully Operational');
            $table->timestamps();
        });

        Schema::create('field_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained('state_profiles')->cascadeOnDelete();
            $table->string('title');
            $table->text('content');
            $table->string('author');
            $table->string('severity')->default('Normal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_updates');
        Schema::dropIfExists('state_profiles');
        Schema::dropIfExists('training_attendances');
        Schema::dropIfExists('training_modules');
        Schema::dropIfExists('pdps');
        Schema::dropIfExists('evidence_custodies');
        Schema::dropIfExists('control_deviations');
        Schema::dropIfExists('rca_findings');
        Schema::dropIfExists('investigation_cases');
    }
};
