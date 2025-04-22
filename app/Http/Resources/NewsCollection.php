<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Http\JsonResponse;

class NewsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'data' => $this->collection,
        ];
        
        // Eğer kaynak bir sayfalayıcı ise, sayfalama bilgilerini ekle
        if ($this->resource instanceof AbstractPaginator) {
            $data['pagination'] = [
                'total' => $this->resource->total(),
                'count' => $this->resource->count(),
                'per_page' => $this->resource->perPage(),
                'current_page' => $this->resource->currentPage(),
                'total_pages' => $this->resource->lastPage(),
                'links' => [
                    'first' => $this->resource->url(1),
                    'last' => $this->resource->url($this->resource->lastPage()),
                    'prev' => $this->resource->previousPageUrl(),
                    'next' => $this->resource->nextPageUrl(),
                ],
            ];
        }
        
        return $data;
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