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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('nm_user');
            $table->date('dob_user');
            $table->string('mobile_user')->unique();
            $table->integer('kd_departemen');
            $table->integer('hak_akses');
            $table->integer('kd_cabang');
            $table->text('profile_img')->default('profile_img/default_user_thumb.webp');
            $table->string('password')->default(bcrypt('Corp12345$'));
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
