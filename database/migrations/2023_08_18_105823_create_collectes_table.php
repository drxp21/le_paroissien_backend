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
        Schema::create('collectes', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
			$table->text('description');
			$table->string('minimum')->default(0);
            $table->date('date_debut');
            $table->date('date_cloture');
            $table->string('objectif');
            $table->string('couverture');
            $table->boolean('toutlemonde')->default(false);
            $table->foreignId('institution_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collectes');
    }
};
