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
        Schema::create('demande_messes', function (Blueprint $table) {
            $table->id();
			$table->foreignId('paroissien_id')->constrained()->nullable();
			$table->text('intention');
			$table->date('date');
			$table->string('montant');
            $table->string('heure');
            $table->foreignId('institution_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_messes');
    }
};
