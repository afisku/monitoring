<?php

use App\Models\Unit;
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
            $table->enum('jenis_kelamin', array_column(JenisKelaminEnum::cases(), 'value'));
            $table->string('telp');
            $table->string('email')->unique();
            $table->string('negara');
            $table->string('provinsi');
            $table->string('kab_kota');
            $table->string('alamat');
            $table->foreignIdFor(Unit::class, 'unit_id')
            ->nullable()
            ->references('id')
            ->on('unit')
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
