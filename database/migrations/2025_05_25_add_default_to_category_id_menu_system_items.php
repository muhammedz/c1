<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE `menu_system_items` MODIFY `category_id` INT UNSIGNED DEFAULT 1 NULL;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE `menu_system_items` MODIFY `category_id` INT UNSIGNED NOT NULL;');
    }
}; 