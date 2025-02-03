<?php

use App\Models\Unit;
use App\Models\Siswa;
use App\Models\Divisi;
use App\Models\StatusCasis;
use App\Models\TahunAkademik;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('croscektk', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Unit::class, 'unit_id')
                ->nullable()
                ->references('id')
                ->on('units')
                ->nullOnDelete();
            $table->foreignIdFor(Siswa::class, 'siswa_id')
                ->references('id')
                ->on('siswa')
                ->cascadeOnDelete();
            $table->enum('biodata', ['PERBAIKAN', 'BELUM DIISI', 'ACC'])->default('ACC')->nullable();
            $table->enum('dokumen', ['PERBAIKAN', 'BELUM DIISI', 'ACC'])->default('ACC')->nullable();
            $table->text('permintaan')->nullable();
            $table->text('note')->nullable();
            $table->enum('anak_gtk', ['YA', 'TIDAK'])->default('TIDAK')->nullable();
            $table->foreignIdFor(Divisi::class, 'divisi_id')
                ->nullable()
                ->references('id')
                ->on('divisi')
                ->nullOnDelete();
            $table->string('nama_GTK')->nullable();
            $table->foreignIdFor(StatusCasis::class, 'status_casis_id')
                ->nullable()
                ->references('id')
                ->on('status_casis')
                ->nullOnDelete();
            $table->foreignIdFor(TahunAkademik::class, 'tahun_akademik_id')
                ->references('id')
                ->on('tahun_akademik')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('croscektk');
    }
};
