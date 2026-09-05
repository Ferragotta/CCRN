<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('travel_requests', function (Blueprint $table) {
            $table->id();
            $table->string('travel_ref')->unique();
            $table->string('traveler_name');
            $table->string('destination');
            $table->text('purpose');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('airline')->nullable();
            $table->string('flight_number')->nullable();
            $table->decimal('ticket_cost', 12, 2)->default(0);
            $table->string('status')->default('Approved'); // Approved, Ticket Issued, Completed, Cancelled
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('ticket_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_request_id')->constrained('travel_requests')->cascadeOnDelete();
            $table->string('ticket_number');
            $table->string('vendor_name');
            $table->string('pnr_code');
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('Pending Boarding Pass'); // Pending Boarding Pass, Cleared, Disbursed
            $table->string('boarding_pass_url')->nullable();
            $table->timestamp('boarding_pass_uploaded_at')->nullable();
            $table->timestamps();
        });

        Schema::create('vendor_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_request_id')->constrained('travel_requests')->cascadeOnDelete();
            $table->string('vendor_name');
            $table->decimal('amount', 12, 2);
            $table->string('payment_status')->default('Locked (Awaiting Boarding Pass)'); // Locked (Awaiting Boarding Pass), Cleared, Paid
            $table->timestamp('disbursed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_payments');
        Schema::dropIfExists('ticket_purchases');
        Schema::dropIfExists('travel_requests');
    }
};
