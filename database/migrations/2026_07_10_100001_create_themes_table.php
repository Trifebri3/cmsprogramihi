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
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('primary_color')->default('#2E7D32');
            $table->string('secondary_color')->default('#FFFFFF');
            $table->string('accent_color')->default('#FFB300');
            $table->string('font_heading')->default('Poppins');
            $table->string('font_body')->default('Inter');
            $table->json('layout_config')->nullable();
            $table->text('custom_css')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
