<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Güvenlik Başlıkları Middleware'i
 * 
 * Tüm HTTP yanıtlarına güvenlik başlıklarını ekler.
 * Bu başlıklar web uygulamasını çeşitli güvenlik tehditlerine karşı korur.
 */
class SecurityHeadersMiddleware
{
    /**
     * İsteği işle ve güvenlik başlıklarını ekle
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Güvenlik başlıklarını ekle
        $this->addSecurityHeaders($response);
        
        return $response;
    }
    
    /**
     * Response'a güvenlik başlıklarını ekler
     *
     * @param Response $response
     * @return void
     */
    private function addSecurityHeaders(Response $response): void
    {
        $securityConfig = config('security.headers', []);
        
        // X-Frame-Options: Clickjacking koruması
        if (isset($securityConfig['x_frame_options'])) {
            $response->headers->set('X-Frame-Options', $securityConfig['x_frame_options']);
        }
        
        // X-Content-Type-Options: MIME sniffing koruması
        if (isset($securityConfig['x_content_type_options'])) {
            $response->headers->set('X-Content-Type-Options', $securityConfig['x_content_type_options']);
        }
        
        // Referrer-Policy: Referrer bilgisi kontrolü
        if (isset($securityConfig['referrer_policy'])) {
            $response->headers->set('Referrer-Policy', $securityConfig['referrer_policy']);
        }
        
        // Strict-Transport-Security: HTTPS zorlama (sadece HTTPS bağlantılarda)
        if ($this->isHttpsRequest() && isset($securityConfig['strict_transport_security'])) {
            $response->headers->set('Strict-Transport-Security', $securityConfig['strict_transport_security']);
        }
        
        // Content-Security-Policy: XSS ve injection koruması (geçici olarak devre dışı)
        // if (isset($securityConfig['content_security_policy'])) {
        //     $response->headers->set('Content-Security-Policy', $securityConfig['content_security_policy']);
        // }
        
        // Content-Security-Policy-Report-Only: Test modu (opsiyonel)
        if (isset($securityConfig['csp_report_only'])) {
            $response->headers->set('Content-Security-Policy-Report-Only', $securityConfig['csp_report_only']);
        }
        
        // Ek güvenlik başlıkları
        $this->addAdditionalSecurityHeaders($response, $securityConfig);
    }
    
    /**
     * Ek güvenlik başlıklarını ekler
     *
     * @param Response $response
     * @param array $config
     * @return void
     */
    private function addAdditionalSecurityHeaders(Response $response, array $config): void
    {
        // X-XSS-Protection: XSS filtreleme (modern tarayıcılarda deprecated ama uyumluluk için)
        if (isset($config['x_xss_protection'])) {
            $response->headers->set('X-XSS-Protection', $config['x_xss_protection']);
        }
        
        // Permissions-Policy: Tarayıcı özelliklerini kontrol etme
        if (isset($config['permissions_policy'])) {
            $response->headers->set('Permissions-Policy', $config['permissions_policy']);
        }
        
        // X-DNS-Prefetch-Control: DNS prefetch kontrolü
        if (isset($config['x_dns_prefetch_control'])) {
            $response->headers->set('X-DNS-Prefetch-Control', $config['x_dns_prefetch_control']);
        }
    }
    
    /**
     * HTTPS bağlantısı olup olmadığını kontrol eder
     *
     * @return bool
     */
    private function isHttpsRequest(): bool
    {
        return request()->isSecure() || 
               request()->header('X-Forwarded-Proto') === 'https' ||
               request()->header('X-Forwarded-SSL') === 'on';
    }
} 