<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->integer("event_id");
            $table->string("username");
            $table->string("email", 320);
            $table->string("phone_number");
            $table->timestamps();
            $table->foreign('event_id')->references('id')->on('events');

            // Restricción de integridad para impedir
            // que un mismo email reserve dos veces en
            // un evento.
            $table->unique(['event_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
