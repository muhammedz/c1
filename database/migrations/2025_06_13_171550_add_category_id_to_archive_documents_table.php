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
        // Önce archive_document_categories tablosuna archive_id ekleyelim
        Schema::table('archive_document_categories', function (Blueprint $table) {
            $table->foreignId('archive_id')->after('id')->constrained('archives')->onDelete('cascade');
        });

        // Sonra archive_documents tablosuna category_id ekleyelim
        Schema::table('archive_documents', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('archive_id')->constrained('archive_document_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('archive_documents', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::table('archive_document_categories', function (Blueprint $table) {
            $table->dropForeign(['archive_id']);
            $table->dropColumn('archive_id');
        });
    }
};
