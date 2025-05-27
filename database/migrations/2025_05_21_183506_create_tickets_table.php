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
            $table->unsignedBigInteger('event_id');
            $table->boolean('is_free')->default(false);
            $table->string('type');
            $table->float('price')->default(0);
            $table->integer('capacity');
            $table->unsignedBigInteger('created_by_id');
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('created_by_id')->references('id')->on('users')->restrictOnDelete();
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
