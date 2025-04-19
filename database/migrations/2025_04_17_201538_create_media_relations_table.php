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
        Schema::create('media_relations', function (Blueprint $table) {
            $table->id();
            $table->string('file_path');
            $table->string('related_to'); // slider, news, page vb.
            $table->unsignedBigInteger('related_id');
            $table->index(['related_to', 'related_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_relations');
    }
};
