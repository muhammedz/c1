<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateImagesToPublic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:migrate-to-public';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Storage klasöründeki dosyaları public klasörüne taşır';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Resim taşıma işlemi başlatılıyor...');
        
        // storage/app/public klasöründeki tüm dosyaları al
        $files = Storage::disk('public')->allFiles();
        $count = count($files);
        
        $this->info("Toplam {$count} dosya bulundu.");
        
        $bar = $this->output->createProgressBar($count);
        $bar->start();
        
        $copied = 0;
        $errors = 0;
        
        foreach ($files as $file) {
            $sourceContents = Storage::disk('public')->get($file);
            
            // Hedef dizini oluştur
            $targetDir = 'uploads/' . dirname($file);
            if (!file_exists(public_path($targetDir)) && !empty($targetDir)) {
                mkdir(public_path($targetDir), 0755, true);
            }
            
            $targetPath = 'uploads/' . $file;
            
            try {
                file_put_contents(public_path($targetPath), $sourceContents);
                $copied++;
            } catch (\Exception $e) {
                $this->error("Hata: {$file} dosyası kopyalanamadı: " . $e->getMessage());
                $errors++;
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        
        $this->info("İşlem tamamlandı!");
        $this->info("Başarıyla kopyalanan dosya sayısı: {$copied}");
        
        if ($errors > 0) {
            $this->error("Hata oluşan dosya sayısı: {$errors}");
        }
        
        return Command::SUCCESS;
    }
} 