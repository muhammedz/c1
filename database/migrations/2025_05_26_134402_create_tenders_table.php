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
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // İhale Konusu
            $table->string('unit'); // İhale Birimi
            $table->text('summary')->nullable(); // İhale Kısa Özet
            $table->string('kik_no')->nullable(); // KİK Kayıt No
            $table->text('address')->nullable(); // İdare'nin Adresi
            $table->string('phone')->nullable(); // İdare'nin Telefon Numarası
            $table->string('fax')->nullable(); // İdare'nin Faks
            $table->string('email')->nullable(); // İdare'nin E-Postası
            $table->string('document_url')->nullable(); // Döküman URL
            $table->text('description')->nullable(); // İhale Konusu, Hizmetin Niteliği, Türü ve Miktarı
            $table->string('delivery_place')->nullable(); // Teslim Yeri
            $table->string('delivery_date')->nullable(); // Teslim Tarihi
            $table->text('tender_address')->nullable(); // İhale'nin Yapılacağı Adres
            $table->dateTime('tender_datetime')->nullable(); // İhale Tarihi/Saati
            $table->longText('content')->nullable(); // İhale Metni
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active'); // İhale Durumu
            $table->string('slug')->unique(); // SEO dostu URL için
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenders');
    }
};
