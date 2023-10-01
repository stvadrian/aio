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
        Schema::create('menu_master_header', function (Blueprint $table) {
            $table->id();
            $table->string('menu_header_name');
            $table->integer('menu_header_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_master_header');
    }
};
