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
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->boolean('verified')->default(false);
            $table->string('statut');
            $table->string('denomination');
            $table->string('diocese')->nullable();
            $table->string('doyenne')->nullable();
            $table->string('adresse');
            $table->string('telephonefixe');
            $table->string('telephonemobile');
            $table->string('emailinstitution');
            $table->string('slogan')->nullable();
            $table->string('nomresp');
            $table->string('prenomresp');
            $table->string('titreresp');
            $table->string('emailresp');
            $table->string('emaildemandeur');
            $table->string('operateur')->nullable();
            $table->string('numcomptemarchand')->nullable();
            $table->string('titulairecompte')->nullable();
            $table->string('codebanque')->nullable();
            $table->string('codeguichet')->nullable();
            $table->string('numerocompte')->nullable();
            $table->string('rib')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
