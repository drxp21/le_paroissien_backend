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
        Schema::create('paroissien_pelerinages', function (Blueprint $table) {
            $table->id();
            $table->string('montant');
            $table->string('operateur')->nullable();
            $table->string('nomBeneficiare');
            $table->string('numeroBeneficiare');
            $table->enum('moyen',['bus','marche']);
            $table->enum('pour',['soi','autrui']);
            $table->foreignId('institution_id')->constrained();
            $table->foreignId('pelerinage_id')->constrained();
            $table->foreignId('paroissien_id')->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paroissien_pelerinages');
    }
};
