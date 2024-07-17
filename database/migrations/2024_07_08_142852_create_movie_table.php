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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->boolean('adult')->default(0);
            $table->string('title');
            $table->text('overview');
            $table->integer('popularity')->default(0);
            $table->float('vote_average')->default(0);
            $table->integer('vote_count')->default(0);
            $table->date('release_date');
            $table->string('poster_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
