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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('event_categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('cover_image')->nullable();
            $table->dateTime('start_date'); // Etkinlik başlangıç tarihi ve saati
            $table->dateTime('end_date')->nullable(); // Etkinlik bitiş tarihi ve saati
            $table->string('location')->nullable(); // Etkinlik yeri
            $table->string('address')->nullable(); // Detaylı adres bilgisi
            $table->string('organizer')->nullable(); // Organizatör/düzenleyen kurum
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('show_on_homepage')->default(false);
            $table->boolean('is_featured')->default(false); // Öne çıkarılan etkinlik
            $table->boolean('register_required')->default(false); // Kayıt gerektiriyor mu?
            $table->string('register_url')->nullable(); // Kayıt linki
            $table->integer('max_participants')->nullable(); // Maksimum katılımcı sayısı
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
