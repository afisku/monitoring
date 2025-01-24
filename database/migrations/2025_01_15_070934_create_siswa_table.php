<?php

use App\Models\Unit;
use App\Models\TahunAkademik;
use App\Enums\JenisKelaminEnum;
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
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('va')->unique();
            $table->string('nm_siswa');
            $table->string('tempat_lahir')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->enum('jenis_kelamin', array_column(JenisKelaminEnum::cases(), 'value'))->nullable();
            $table->string('telp')->nullable();
            $table->string('email')->nullable();
            $table->string('negara')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kab_kota')->nullable();
            $table->string('alamat')->nullable();
            $table->string('asal_sekolah')->nullable();
            $table->string('kelas')->nullable();
            $table->string('diskon')->nullable();
            $table->foreignIdFor(TahunAkademik::class, 'tahun_akademik_id')
                ->references('id')
                ->on('tahun_akademik')
                ->cascadeOnDelete();
            $table->foreignIdFor(Unit::class, 'unit_id')
                ->nullable()
                ->references('id')
                ->on('units')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
