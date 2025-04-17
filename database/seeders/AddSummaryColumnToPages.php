<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddSummaryColumnToPages extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasColumn('pages', 'summary')) {
            DB::statement('ALTER TABLE pages ADD COLUMN summary TEXT NULL AFTER content');
            $this->command->info('summary column added to pages table.');
        } else {
            $this->command->info('summary column already exists in pages table.');
        }
    }
} 