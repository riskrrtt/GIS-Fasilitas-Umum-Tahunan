<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGisTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. tb_desa
        Schema::create('tb_desa', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('colour')->nullable();
            $table->longText('area')->nullable(); // Stores coordinate JSON
            $table->timestamps();
        });

        // 2. tb_jenis_potensi
        Schema::create('tb_jenis_potensi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_desa')->nullable(); // Based on model fillable
            $table->string('jenis');
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        // 3. tb_jenjang_sekolah
        Schema::create('tb_jenjang_sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('jenjang');
            $table->timestamps();
        });

        // 4. tb_agama (Inferred from TempatIbadah relation)
        Schema::create('tb_agama', function (Blueprint $table) {
            $table->id();
            $table->string('agama');
            $table->timestamps();
        });

        // 5. tb_sekolah
        Schema::create('tb_sekolah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_desa')->constrained('tb_desa')->onDelete('cascade');
            $table->foreignId('id_jenjang')->nullable()->constrained('tb_jenjang_sekolah')->onDelete('set null');
            $table->foreignId('id_jenis_potensi')->nullable()->constrained('tb_jenis_potensi')->onDelete('set null');
            $table->string('nama');
            $table->string('pict')->nullable();
            $table->string('jenis_sekolah')->nullable(); // Negeri/Swasta
            $table->text('alamat')->nullable();
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->softDeletes(); // deleted_at
            $table->timestamps();
        });

        // 6. tb_pasar
        Schema::create('tb_pasar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_desa')->constrained('tb_desa')->onDelete('cascade');
            $table->foreignId('id_jenis_potensi')->nullable()->constrained('tb_jenis_potensi')->onDelete('set null');
            $table->string('nama');
            $table->string('pict')->nullable();
            $table->text('alamat')->nullable();
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // 7. tb_tempat_ibadah
        Schema::create('tb_tempat_ibadah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_desa')->constrained('tb_desa')->onDelete('cascade');
            $table->foreignId('id_jenis_potensi')->nullable()->constrained('tb_jenis_potensi')->onDelete('set null');
            $table->foreignId('id_agama')->nullable()->constrained('tb_agama')->onDelete('set null');
            $table->string('nama');
            $table->string('pict')->nullable();
            $table->text('alamat')->nullable();
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        // 8. tb_admin
        Schema::create('tb_admin', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_admin');
        Schema::dropIfExists('tb_tempat_ibadah');
        Schema::dropIfExists('tb_pasar');
        Schema::dropIfExists('tb_sekolah');
        Schema::dropIfExists('tb_agama');
        Schema::dropIfExists('tb_jenjang_sekolah');
        Schema::dropIfExists('tb_jenis_potensi');
        Schema::dropIfExists('tb_desa');
    }
}
