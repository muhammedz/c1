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
        Schema::create('footer_settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('company_name')->default('Çankaya');
            $table->string('company_subtitle')->default('BELEDİYESİ');
            $table->string('address_line1')->default('Ziya Gökalp Caddesi');
            $table->string('address_line2')->default('No: 11 Kızılay/Ankara');
            $table->string('contact_center_title')->default('Çankaya İletişim Merkezi');
            $table->string('contact_center_phone')->default('444 06 01');
            $table->string('whatsapp_title')->default('Whatsapp Hattı');
            $table->string('whatsapp_number')->default('0(505) 167 19 67');
            $table->string('email_title')->default('E-Posta');
            $table->string('email_address')->default('iletisimmerkezi@cankaya.bel.tr');
            $table->string('kep_title')->default('Kep Adresi');
            $table->string('kep_address')->default('cankayabelediyesi@hs01.kep.tr');
            $table->text('copyright_left')->nullable();
            $table->text('copyright_right')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('footer_settings');
    }
};
