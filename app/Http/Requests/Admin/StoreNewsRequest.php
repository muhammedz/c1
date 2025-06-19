<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Şimdilik herkese izin veriyoruz, daha sonra yetkilendirme eklenecek
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Türkçe tarih formatını (dd.mm.yyyy) Y-m-d formatına çevir
        if ($this->published_at) {
            $this->merge([
                'published_at' => $this->convertDateFormat($this->published_at)
            ]);
        }
        
        if ($this->end_date) {
            $this->merge([
                'end_date' => $this->convertDateFormat($this->end_date)
            ]);
        }
    }
    
    /**
     * Convert Turkish date format (dd.mm.yyyy) to Y-m-d format
     */
    private function convertDateFormat($date)
    {
        if (!$date) return null;
        
        // Eğer zaten doğru formattaysa olduğu gibi döndür
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return $date;
        }
        
        // dd.mm.yyyy formatını Y-m-d formatına çevir
        if (preg_match('/^(\d{2})\.(\d{2})\.(\d{4})$/', $date, $matches)) {
            return $matches[3] . '-' . $matches[2] . '-' . $matches[1];
        }
        
        return $date;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|string',
            'status' => 'required|in:published,draft',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:news_categories,id',
            'published_at' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:published_at',
            'summary' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'gallery' => 'nullable|array',
            'gallery.*' => 'string',
            'filemanagersystem_gallery' => 'nullable|json',
            'is_headline' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'tags' => 'nullable|string',
            'files' => 'nullable|array',
            'files.*' => 'file|max:51200|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',
            'names' => 'nullable|array',
            'names.*' => 'nullable|string|max:255'
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Başlık alanı zorunludur.',
            'content.required' => 'İçerik alanı zorunludur.',
            'status.required' => 'Durum alanı zorunludur.',
            'categories.required' => 'En az bir kategori seçmelisiniz.',
            'categories.min' => 'En az bir kategori seçmelisiniz.',
            'end_date.after_or_equal' => 'Bitiş tarihi, yayınlanma tarihinden sonra olmalıdır.'
        ];
    }
} 