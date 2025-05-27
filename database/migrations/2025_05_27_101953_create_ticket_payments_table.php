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
        Schema::create('ticket_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_purchase_id');
            $table->double('amount_paid');
            $table->string('system_reference_number');
            $table->string('msisdn');
            $table->string('transaction_number');
            $table->text('narration');
            $table->timestamps();

            $table->foreign('ticket_purchase_id')->references('id')->on('ticket_purchases')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_payments');
    }
};
