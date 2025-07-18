<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Carbon\Carbon;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'user_name',
        'model_type',
        'model_id',
        'action',
        'old_values',
        'new_values',
        'description',
        'ip_address',
        'user_agent',
        'url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that performed the activity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the model that the activity was performed on.
     */
    public function subject(): MorphTo
    {
        return $this->morphTo('model');
    }

    /**
     * Scope to filter by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by model type.
     */
    public function scopeByModelType($query, $modelType)
    {
        return $query->where('model_type', $modelType);
    }

    /**
     * Scope to filter by action.
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope to get recent activities.
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    /**
     * Get the action in Turkish.
     */
    public function getActionInTurkish(): string
    {
        $actions = [
            'created' => 'Oluşturuldu',
            'updated' => 'Güncellendi',
            'deleted' => 'Silindi',
            'restored' => 'Geri Yüklendi',
            'force_deleted' => 'Kalıcı Olarak Silindi',
            'uploaded' => 'Yüklendi',
            'file_deleted' => 'Silindi',
            'bulk_deleted' => 'Toplu Silindi',
            'file_edited' => 'Düzenlendi',
        ];

        return $actions[$this->action] ?? $this->action;
    }

    /**
     * Get the model name in Turkish.
     */
    public function getModelNameInTurkish(): string
    {
        $modelNames = [
            'App\\Models\\News' => 'Haber',
            'App\\Models\\Page' => 'Sayfa',
            'App\\Models\\Service' => 'Hizmet',
            'App\\Models\\Event' => 'Etkinlik',
            'App\\Models\\User' => 'Kullanıcı',
            'App\\Models\\Archive' => 'Arşiv',
            'App\\Models\\ArchiveDocument' => 'Arşiv Belgesi',
            'App\\Models\\Project' => 'Proje',
            'App\\Models\\ProjectCategory' => 'Proje Kategorisi',
            'App\\Models\\Slider' => 'Slider',
            'App\\Models\\NewsCategory' => 'Haber Kategorisi',
            'App\\Models\\ServiceCategory' => 'Hizmet Kategorisi',
            'App\\Models\\Mayor' => 'Başkan',
            'App\\Models\\MayorContent' => 'Başkan İçeriği',
            'App\\Models\\Setting' => 'Ayar',
            'App\\Models\\MenuSystem' => 'Menü Sistemi',
            'App\\Models\\MenuSystemItem' => 'Menü Öğesi',
            'App\\Models\\CorporateMember' => 'Kurumsal Üye',
            'App\\Models\\CorporateCategory' => 'Kurumsal Kategori',
            'App\\Models\\GuidePlace' => 'Rehber Yeri',
            'App\\Models\\GuideCategory' => 'Rehber Kategorisi',
            'App\\Models\\Mudurluk' => 'Müdürlük',
            'App\\Models\\Announcement' => 'Duyuru',
            'App\\Models\\Tender' => 'İhale',
            'App\\Models\\FileManagerSystem\\Media' => 'Dosya',
            'App\\Models\\FileManagerSystem\\Folder' => 'Klasör',
            'App\\Models\\FileManagerSystem\\Category' => 'Dosya Kategorisi',
            'App\\Models\\FileManagerSystem\\MediaRelation' => 'Dosya İlişkisi',
        ];
        
        return $modelNames[$this->model_type] ?? class_basename($this->model_type);
    }

    /**
     * Get a formatted description of the activity.
     */
    public function getFormattedDescription(): string
    {
        $userName = $this->user_name ?? 'Bilinmeyen Kullanıcı';
        $modelName = $this->getModelNameInTurkish();
        $action = $this->getActionInTurkish();

        if ($this->description) {
            return $this->description;
        }

        return "{$userName} tarafından {$modelName} {$action}";
    }

    /**
     * Get changes summary.
     */
    public function getChangesSummary(): array
    {
        $changes = [];
        
        if ($this->old_values && $this->new_values) {
            foreach ($this->new_values as $key => $newValue) {
                $oldValue = $this->old_values[$key] ?? null;
                if ($oldValue !== $newValue) {
                    $changes[$key] = [
                        'old' => $oldValue,
                        'new' => $newValue
                    ];
                }
            }
        }

        return $changes;
    }

    /**
     * Create activity log entry.
     */
    public static function createLog(
        $model,
        string $action,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null
    ): self {
        $user = auth()->user();
        $request = request();

        return self::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'model_type' => get_class($model),
            'model_id' => $model->id ?? null,
            'action' => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'description' => $description,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'url' => $request?->fullUrl(),
        ]);
    }
}
