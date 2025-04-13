<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EventSettings;

class CreateEventSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:event-settings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create default event settings if not exist';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $settings = EventSettings::first();

        if (!$settings) {
            $settings = new EventSettings();
            $settings->is_active = true;
            $settings->title = 'Etkinlikler';
            $settings->description = 'Tüm etkinliklerimizi takip edebilirsiniz';
            $settings->section_title = 'Yaklaşan Etkinlikler';
            $settings->section_subtitle = 'Katılabileceğiniz etkinlikler';
            $settings->homepage_limit = 6;
            $settings->show_past_events = false;
            $settings->show_category_filter = true;
            $settings->show_map = true;
            $settings->save();

            $this->info('Etkinlik ayarları başarıyla oluşturuldu.');
        } else {
            $this->info('Etkinlik ayarları zaten mevcut.');
            $this->table(
                ['ID', 'Aktif', 'Başlık', 'Bölüm Başlığı', 'Ana sayfa limit'],
                [[$settings->id, $settings->is_active ? 'Evet' : 'Hayır', $settings->title, $settings->section_title, $settings->homepage_limit]]
            );
        }

        return 0;
    }
}
