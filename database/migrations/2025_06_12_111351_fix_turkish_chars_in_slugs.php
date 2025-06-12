<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Helpers\SlugHelper;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Corporate Categories slug'larını düzelt
        $this->fixSlugsForTable('corporate_categories', 'name');
        
        // Corporate Members slug'larını düzelt
        $this->fixSlugsForTable('corporate_members', 'name');
        
        // News slug'larını düzelt
        $this->fixSlugsForTable('news', 'title');
        
        // Pages slug'larını düzelt
        $this->fixSlugsForTable('pages', 'title');
        
        // Services slug'larını düzelt
        $this->fixSlugsForTable('services', 'title');
        
        // Projects slug'larını düzelt
        $this->fixSlugsForTable('projects', 'title');
        
        // Mudurlukler slug'larını düzelt
        $this->fixSlugsForTable('mudurlukler', 'name');
        
        // News Categories slug'larını düzelt
        $this->fixSlugsForTable('news_categories', 'name');
        
        // Service Categories slug'larını düzelt
        $this->fixSlugsForTable('service_categories', 'name');
        
        // Page Categories slug'larını düzelt
        $this->fixSlugsForTable('page_categories', 'name');
    }
    
    /**
     * Belirtilen tablo için slug'ları düzelt
     */
    private function fixSlugsForTable(string $tableName, string $sourceField): void
    {
        // Tablo var mı kontrol et
        if (!Schema::hasTable($tableName)) {
            return;
        }
        
        // Slug alanı var mı kontrol et
        if (!Schema::hasColumn($tableName, 'slug')) {
            return;
        }
        
        // Kaynak alan var mı kontrol et
        if (!Schema::hasColumn($tableName, $sourceField)) {
            return;
        }
        
        $records = DB::table($tableName)->get();
        
        foreach ($records as $record) {
            $sourceText = $record->$sourceField;
            $currentSlug = $record->slug;
            
            if (empty($sourceText)) {
                continue;
            }
            
            // Yeni slug oluştur
            $newSlug = SlugHelper::create($sourceText);
            
            // Mevcut slug ile aynıysa güncelleme yapmaya gerek yok
            if ($currentSlug === $newSlug) {
                continue;
            }
            
            // Benzersizlik kontrolü
            $counter = 1;
            $uniqueSlug = $newSlug;
            
            while (DB::table($tableName)
                ->where('slug', $uniqueSlug)
                ->where('id', '!=', $record->id)
                ->exists()) {
                $uniqueSlug = $newSlug . '-' . $counter;
                $counter++;
            }
            
            // Slug'ı güncelle
            DB::table($tableName)
                ->where('id', $record->id)
                ->update(['slug' => $uniqueSlug]);
            
            echo "✅ {$tableName}: '{$currentSlug}' → '{$uniqueSlug}'\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Bu migration geri alınamaz çünkü eski slug'ları kaybettik
        // Gerekirse manuel olarak düzeltilmeli
        echo "⚠️  Bu migration geri alınamaz. Slug değişiklikleri kalıcıdır.\n";
    }
};
