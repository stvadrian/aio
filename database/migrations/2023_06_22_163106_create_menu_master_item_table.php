<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $connection = 'sqlite';

    public function up(): void
    {
        Schema::create('menu_master_item', function (Blueprint $table) {
            $table->id();
            $table->integer('master_header');
            $table->string('menu_item_name');
            $table->string('menu_item_link');
            $table->string('menu_item_file');
            $table->string('menu_function');
            $table->integer('menu_item_status');
            $table->string('menu_icon');
            $table->string('modul_departemen');
            $table->integer('hak_akses');
            $table->integer('urutan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_master_item');
    }
};
