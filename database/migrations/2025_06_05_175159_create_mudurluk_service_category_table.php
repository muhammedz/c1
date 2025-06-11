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
        Schema::create('mudurluk_service_category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mudurluk_id');
            $table->unsignedBigInteger('service_category_id');
            $table->timestamps();

            $table->foreign('mudurluk_id')
                ->references('id')
                ->on('mudurlukler')
                ->onDelete('cascade');

            $table->foreign('service_category_id')
                ->references('id')
                ->on('service_categories')
                ->onDelete('cascade');

            $table->unique(['mudurluk_id', 'service_category_id'], 'mudurluk_service_category_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mudurluk_service_category');
    }
};
