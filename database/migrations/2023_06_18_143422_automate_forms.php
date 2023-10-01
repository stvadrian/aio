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
        Schema::create('automate_forms', function (Blueprint $table) {
            $table->id();
            $table->string('form_name');
            $table->string('form_name_e');
            $table->string('link_form');
            $table->string('description')->nullable();
            $table->integer('status');
            $table->text('background_path')->nullable();
            $table->text('qr_code')->nullable();
            $table->string('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('automate_forms');
    }
};
