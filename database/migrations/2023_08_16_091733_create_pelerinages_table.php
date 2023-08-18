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
        Schema::create('pelerinages', function (Blueprint $table) {
            $table->id();
            $table->string('edition');
			$table->string('theme');
			$table->date('dateLimCar');
			$table->date('dateLimMarche');
			$table->string('fraisCar');
			$table->string('fraisMarche');
			$table->string('imageCover');
			$table->text('description');
            $table->foreignId('institution_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelerinages');
    }
};
