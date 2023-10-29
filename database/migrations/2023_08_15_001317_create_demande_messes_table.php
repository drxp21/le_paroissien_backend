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
			$table->text('intention');
			$table->date('date');
			$table->string('montant');
			$table->string('operateur')->nullable();
            $table->string('heure');
            $table->string('type');
            $table->boolean('anonyme')->default(false);
            $table->foreignId('institution_id')->constrained();
			$table->foreignId('paroissien_id')->nullable()->constrained();
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
