<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'content' => $this->content,
            'image' => $this->filemanagersystem_image_url ?? null,
            'image_alt' => $this->filemanagersystem_image_alt,
            'image_title' => $this->filemanagersystem_image_title,
            'is_headline' => $this->is_headline,
            'headline_order' => $this->headline_order,
            'is_featured' => $this->is_featured,
            'view_count' => $this->views,
            'status' => $this->status,
            'published_at' => $this->published_at ? $this->published_at->format('Y-m-d H:i:s') : null,
            'category' => new NewsCategoryResource($this->whenLoaded('category')),
            'tags' => NewsTagResource::collection($this->whenLoaded('tags')),
            'gallery' => $this->when($this->relationLoaded('media'), function () {
                return $this->galleryImages()->map(function ($media) {
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
} 