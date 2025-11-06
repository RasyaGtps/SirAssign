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
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mapel_id')->constrained('mapels')->onDelete('cascade');
            $table->string('judul');
            $table->text('pertanyaan');
            $table->text('ringkasan')->nullable()->comment('Ringkasan hubungan soal dengan materi dari AI');
            $table->enum('tingkat_kesulitan', ['mudah', 'normal', 'susah'])->default('normal');
            $table->decimal('similarity_score', 8, 4)->nullable()->comment('Score similarity dengan materi');
            $table->json('matched_materials')->nullable()->comment('Materi yang match dengan pertanyaan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
