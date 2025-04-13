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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->string('bg_color')->default('#fff3cd');
            $table->string('text_color')->default('#856404');
            $table->string('border_color')->default('#ffeeba');
            $table->string('icon')->default('info');
            $table->json('display_pages')->nullable();
            $table->integer('max_views_per_user')->default(0); // 0 = sınırsız
            $table->enum('position', ['top', 'bottom', 'left', 'right'])->default('bottom');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
