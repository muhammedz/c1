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
        // Önce ilişki tablosunu kontrol edelim
        if (!Schema::hasTable('hedef_kitle_news')) {
            Schema::create('hedef_kitle_news', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('hedef_kitle_id');
                $table->unsignedBigInteger('news_id');
                $table->timestamps();

                $table->unique(['hedef_kitle_id', 'news_id']);
                
                $table->foreign('hedef_kitle_id')->references('id')->on('hedef_kitleler')->onDelete('cascade');
            });
        } else {
            // Eğer tablo varsa timestamps ekleme
            Schema::table('hedef_kitle_news', function (Blueprint $table) {
                if (!Schema::hasColumn('hedef_kitle_news', 'created_at')) {
                    $table->timestamps();
                }
                
                // Kontrol için bir kolon ekleyelim
                if (!Schema::hasColumn('hedef_kitle_news', 'has_been_checked')) {
                    $table->boolean('has_been_checked')->default(true);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hedef_kitle_news', function (Blueprint $table) {
            if (Schema::hasColumn('hedef_kitle_news', 'has_been_checked')) {
                $table->dropColumn('has_been_checked');
            }
        });
    }
};
