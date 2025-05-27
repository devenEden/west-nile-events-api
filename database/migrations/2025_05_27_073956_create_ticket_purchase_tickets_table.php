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
        Schema::create('ticket_purchase_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_purchase_id');
            $table->unsignedBigInteger('ticket_id');
            $table->integer('number_of_tickets')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_purchase_tickets');
    }
};
