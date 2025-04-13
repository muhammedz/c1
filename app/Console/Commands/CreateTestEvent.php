<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\EventCategory;
use Carbon\Carbon;

class CreateTestEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:test-event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test event with future date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Önce bir kategori oluşturalım (eğer yoksa)
        $category = EventCategory::first();
        
        if (!$category) {
            $category = new EventCategory();
            $category->name = 'Test Kategori';
            $category->slug = 'test-kategori';
            $category->description = 'Test kategori açıklaması';
            $category->color = '#3490dc';
            $category->order = 1;
            $category->is_active = true;
            $category->save();
            
            $this->info('Test kategori oluşturuldu: ' . $category->name);
        }
        
        // Şimdi gelecek tarihli bir etkinlik oluşturalım
        $event = new Event();
        $event->title = 'Test Etkinlik - ' . now()->format('d.m.Y');
        $event->slug = 'test-etkinlik-' . now()->format('dmY');
        $event->description = 'Bu bir test etkinliğidir. Bu etkinlik anasayfada etkinlikler bölümünü test etmek için oluşturulmuştur.';
        $event->category_id = $category->id;
        $event->start_date = Carbon::now()->addDays(5)->setHour(14)->setMinute(0);
        $event->end_date = Carbon::now()->addDays(5)->setHour(16)->setMinute(0);
        $event->location = 'Test Lokasyon';
        $event->address = 'Test Adres, No:123, Şehir';
        $event->organizer = 'Test Organizatör';
        $event->order = 1;
        $event->is_active = true;
        $event->show_on_homepage = true;
        $event->is_featured = true;
        $event->register_required = false;
        $event->save();
        
        $this->info('Test etkinlik başarıyla oluşturuldu.');
        $this->table(
            ['ID', 'Başlık', 'Kategori', 'Başlangıç Tarihi', 'Ana Sayfada Göster', 'Aktif'],
            [[$event->id, $event->title, $category->name, $event->start_date->format('d.m.Y H:i'), $event->show_on_homepage ? 'Evet' : 'Hayır', $event->is_active ? 'Evet' : 'Hayır']]
        );
        
        return 0;
    }
}
