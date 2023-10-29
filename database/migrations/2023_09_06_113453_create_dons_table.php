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
        Schema::create('dons', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('type');
            $table->string('auteur')->default('Anonyme');
            $table->string('montant');
            $table->string('operateur')->nullable();
            $table->text('intention')->nullable();
            $table->string('moyen')->nullable();
            $table->foreignId('institution_id')->constrained();
            $table->foreignId('paroissien_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dons');
    }
};
