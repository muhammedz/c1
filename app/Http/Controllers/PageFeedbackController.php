<?php

namespace App\Http\Controllers;

use App\Models\PageFeedback;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

/**
 * Sayfa Geri Bildirim Controller
 * 
 * Bu controller kullanıcıların hizmet sayfalarına verdiği geri bildirimleri yönetir.
 * AJAX istekleri ile çalışır ve JSON response döner.
 */
class PageFeedbackController extends Controller
{
    /**
     * Geri bildirim gönderme işlemi
     * 
     * Kullanıcı "Bu sayfa size yardımcı oldu mu?" sorusuna cevap verdiğinde
     * bu method çalışır ve geri bildirimi veritabanına kaydeder.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Rate limiting - Aynı IP'den çok fazla istek engellenir
        $key = 'page-feedback:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'success' => false,
                'message' => 'Çok fazla istek gönderdiniz. Lütfen daha sonra tekrar deneyin.'
            ], 429);
        }

        // Gelen verileri doğrula
        $validator = Validator::make($request->all(), [
            'page_url' => 'required|string|max:500',
            'page_title' => 'required|string|max:255',
            'is_helpful' => 'required|boolean',
        ], [
            'page_url.required' => 'Sayfa URL\'si gereklidir.',
            'page_url.max' => 'Sayfa URL\'si çok uzun.',
            'page_title.required' => 'Sayfa başlığı gereklidir.',
            'page_title.max' => 'Sayfa başlığı çok uzun.',
            'is_helpful.required' => 'Geri bildirim seçimi gereklidir.',
            'is_helpful.boolean' => 'Geçersiz geri bildirim değeri.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz veri gönderildi.',
                'errors' => $validator->errors()
            ], 422);
        }

        $userIp = $request->ip();
        $pageUrl = $request->input('page_url');
        $pageTitle = $request->input('page_title');
        $isHelpful = $request->boolean('is_helpful');

        // Kullanıcı daha önce bu sayfaya geri bildirim verdi mi kontrol et
        if (PageFeedback::hasUserFeedback($pageUrl, $userIp)) {
            return response()->json([
                'success' => false,
                'message' => 'Bu sayfa için daha önce geri bildirim verdiniz.'
            ], 409);
        }

        try {
            // Geri bildirimi kaydet
            $feedback = PageFeedback::createFeedback(
                $pageUrl,
                $pageTitle,
                $isHelpful,
                $userIp,
                $request->userAgent()
            );

            // Rate limiting sayacını artır
            RateLimiter::hit($key);

            // Güncel istatistikleri getir
            $stats = PageFeedback::getPageStats($pageUrl);

            return response()->json([
                'success' => true,
                'message' => 'Geri bildiriminiz kaydedildi. Teşekkür ederiz!',
                'data' => [
                    'feedback_id' => $feedback->id,
                    'stats' => $stats
                ]
            ], 201);

        } catch (\Exception $e) {
            // Hata logla
            \Log::error('Geri bildirim kaydedilemedi: ' . $e->getMessage(), [
                'page_url' => $pageUrl,
                'user_ip' => $userIp,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Geri bildirim kaydedilirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.'
            ], 500);
        }
    }

    /**
     * Belirli bir sayfa için geri bildirim istatistiklerini getirir
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getPageStats(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'page_url' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz sayfa URL\'si.'
            ], 422);
        }

        $pageUrl = $request->input('page_url');
        $stats = PageFeedback::getPageStats($pageUrl);

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Kullanıcının belirli bir sayfaya geri bildirim verip vermediğini kontrol eder
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function checkUserFeedback(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'page_url' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz sayfa URL\'si.'
            ], 422);
        }

        $pageUrl = $request->input('page_url');
        $userIp = $request->ip();
        
        $hasFeedback = PageFeedback::hasUserFeedback($pageUrl, $userIp);

        return response()->json([
            'success' => true,
            'data' => [
                'has_feedback' => $hasFeedback,
                'stats' => PageFeedback::getPageStats($pageUrl)
            ]
        ]);
    }
}
