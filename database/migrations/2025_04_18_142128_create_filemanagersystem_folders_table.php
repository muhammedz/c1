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
        Schema::create('filemanagersystem_folders', function (Blueprint $table) {
            $table->id();
            $table->string('folder_name');
            $table->string('folder_slug')->unique();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('status')->default(1);
            $table->text('folder_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign key ilişkileri - sistem hazır olduğunda aktifleştirilecek
            // $table->foreign('parent_id')->references('id')->on('filemanagersystem_folders')->onDelete('cascade');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filemanagersystem_folders');
    }
};
