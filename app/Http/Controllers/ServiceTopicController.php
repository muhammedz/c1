<?php

namespace App\Http\Controllers;

use App\Models\ServiceTopic;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceTopicController extends Controller
{
    /**
     * Hizmet konularının listesini göster
     */
    public function index()
    {
        $serviceTopics = ServiceTopic::active()
            ->orderBy('name', 'asc')
            ->withCount(['services' => function ($query) {
                $query->published();
            }])
            ->get();

        // Diğer kategoriler de ekleyelim
        $categories = ServiceCategory::orderBy('name', 'asc')->get();

        return view('front.services.topics', compact('serviceTopics', 'categories'));
    }

    /**
     * Belirli bir hizmet konusunu ve hizmetlerini göster
     */
    public function show($slug)
    {
        $serviceTopic = ServiceTopic::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $services = $serviceTopic->services()
            ->published()
            ->latest()
            ->get();

        // Diğer kategoriler de ekleyelim
        $categories = ServiceCategory::orderBy('name', 'asc')->get();
        $serviceTopics = ServiceTopic::active()->orderBy('name', 'asc')->get();

        // SEO bilgileri
        $pageTitle = $serviceTopic->meta_title ?: $serviceTopic->name . ' | Çankaya Belediyesi';
        $pageDescription = $serviceTopic->meta_description ?: $serviceTopic->description;

        return view('front.services.topic-category', compact(
            'serviceTopic',
            'services',
            'categories',
            'serviceTopics',
            'pageTitle',
            'pageDescription'
        ));
    }
} 