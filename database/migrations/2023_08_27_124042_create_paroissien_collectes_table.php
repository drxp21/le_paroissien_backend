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
        Schema::create('paroissien_collectes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('montant');
            $table->foreignId('collecte_id')->constrained();
            $table->foreignId('paroissien_id')->constrained();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paroissien_collectes');
    }
};
