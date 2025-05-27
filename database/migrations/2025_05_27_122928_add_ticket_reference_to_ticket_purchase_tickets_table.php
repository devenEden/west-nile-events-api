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
        Schema::table('ticket_purchase_tickets', function (Blueprint $table) {
            //
            $table->string('ticket_reference')->nullable();
            $table->boolean('has_entered')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_purchase_tickets', function (Blueprint $table) {
            //
            $table->dropColumn('ticket_reference');
            $table->dropColumn('has_entered');
        });
    }
};
