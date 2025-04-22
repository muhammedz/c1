<?php

namespace App\Http\Resources;

use App\Helpers\CharacterFixer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\JsonResponse;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'content' => $this->formatContent($this->content),
            'image' => $this->filemanagersystem_image_url ?? $this->image ?? null,
            'image_alt' => $this->filemanagersystem_image_alt,
            'image_title' => $this->filemanagersystem_image_title,
            'is_headline' => $this->is_headline,
            'headline_order' => $this->headline_order,
            'is_featured' => $this->is_featured,
            'view_count' => $this->view_count ?? $this->views ?? 0,
            'status' => $this->status,
            'published_at' => $this->published_at ? $this->published_at->format('Y-m-d H:i:s') : null,
            'category' => new NewsCategoryResource($this->whenLoaded('category')),
            'tags' => NewsTagResource::collection($this->whenLoaded('tags')),
            'gallery' => $this->when($this->relationLoaded('media'), function () {
                $galleryImages = $this->galleryImages();
                return $galleryImages->map(function ($media) {
                    return [
                        'id' => $media->id,
                        'url' => asset($media->url),
                        'alt' => $media->alt,
                        'title' => $media->title,
                    ];
                });
            }),
            'meta' => [
                'title' => $this->meta_title,
                'description' => $this->meta_description,
                'keywords' => $this->meta_keywords,
            ],
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
    
    /**
     * İçeriği doğru formata dönüştürür
     * 
     * @param string|null $content
     * @return string|null
     */
    protected function formatContent($content)
    {
        if (empty($content)) {
            return null;
        }
        
        // Doğrudan JSON decode etmeyi deneyelim (başarısız olursa string olarak kalsın)
        if (substr($content, 0, 2) !== '<p' && substr($content, 0, 3) !== '<h1' && substr($content, 0, 3) !== '<h2') {
            $decoded = @json_decode($content);
            if (json_last_error() === JSON_ERROR_NONE && is_string($decoded)) {
                $content = $decoded;
            } else {
                // JSON_DECODE başarısız olduysa Unicode işaretlerini manuel olarak değiştirelim
                $content = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($matches) {
                    return html_entity_decode('&#x' . $matches[1] . ';', ENT_QUOTES, 'UTF-8');
                }, $content);
            }
        }
        
        // Escape karakterleri düzeltme
        $search = [
            '\u003C', '\u003E', '\u003c', '\u003e',  // HTML tags
            '\u0022', '\u0027', '\u0026',           // Quotes and ampersand
            '\u00A0', '\u00a0',                     // Non-breaking space
            '\u003D', '\u003d',                     // Equals sign
            '\r\n', '\n', '\r', '\t',               // Line breaks and tabs
            '\"',                                   // Escaped quotes
            '\\\\'                                  // Double backslash
        ];
        
        $replace = [
            '<', '>', '<', '>',
            '"', "'", '&',
            ' ', ' ',
            '=', '=',
            "\r\n", "\n", "\r", "\t",
            '"',
            '\\'
        ];
        
        $content = str_replace($search, $replace, $content);
        
        // HTML entity'leri çöz (&Ccedil; => Ç)
        $content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        return $content;
    }
    
    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function withResponse($request, $response)
    {
        if ($response instanceof JsonResponse) {
            $response->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | $response->getEncodingOptions());
        }
    }
} 