<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    /**
     * Fields to exclude from activity logging.
     */
    protected array $excludedFields = [
        'created_at',
        'updated_at',
        'deleted_at',
        'remember_token',
        'password',
        'email_verified_at',
    ];

    /**
     * Log model creation.
     */
    public function logCreated(Model $model): void
    {
        $this->createActivityLog($model, 'created', null, $this->getModelAttributes($model));
    }

    /**
     * Log model update.
     */
    public function logUpdated(Model $model): void
    {
        $oldValues = $this->getModelAttributes($model->getOriginal());
        $newValues = $this->getModelAttributes($model->getAttributes());
        
        // Sadece değişen alanları logla
        $changes = $this->getChangedAttributes($oldValues, $newValues);
        
        if (!empty($changes['old']) && !empty($changes['new'])) {
            $this->createActivityLog($model, 'updated', $changes['old'], $changes['new']);
        }
    }

    /**
     * Log model deletion.
     */
    public function logDeleted(Model $model): void
    {
        $this->createActivityLog($model, 'deleted', $this->getModelAttributes($model));
    }

    /**
     * Log model restoration.
     */
    public function logRestored(Model $model): void
    {
        $this->createActivityLog($model, 'restored', null, $this->getModelAttributes($model));
    }

    /**
     * Log model force deletion.
     */
    public function logForceDeleted(Model $model): void
    {
        $this->createActivityLog($model, 'force_deleted', $this->getModelAttributes($model));
    }

    /**
     * Create activity log entry.
     */
    protected function createActivityLog(
        Model $model,
        string $action,
        array $oldValues = null,
        array $newValues = null,
        string $description = null
    ): void {
        try {
            // Eğer özel açıklama verilmemişse, otomatik açıklama oluştur
            if (!$description) {
                $description = $this->generateDescription($model, $action);
            }
            
            ActivityLog::createLog($model, $action, $oldValues, $newValues, $description);
        } catch (\Exception $e) {
            // Sessizce devam et - activity log hatası ana işlemi etkilememelidir
            \Log::error('Activity log creation failed: ' . $e->getMessage(), [
                'model' => get_class($model),
                'model_id' => $model->id ?? null,
                'action' => $action,
            ]);
        }
    }

    /**
     * Generate description for activity log.
     */
    protected function generateDescription(Model $model, string $action): string
    {
        $modelName = $this->getModelNameInTurkish($model);
        $actionText = $this->getActionInTurkish($action);
        
        // Model'in title, name veya başka bir tanımlayıcı alanını bul
        $identifier = $this->getModelIdentifier($model);
        
        if ($identifier) {
            return "\"{$identifier}\" {$modelName} {$actionText}";
        }
        
        return ucfirst($modelName) . " {$actionText}";
    }

    /**
     * Get model identifier (title, name, etc.)
     */
    protected function getModelIdentifier(Model $model): ?string
    {
        // Öncelik sırasına göre alanları kontrol et
        $fields = ['title', 'name', 'label', 'subject', 'username', 'email'];
        
        foreach ($fields as $field) {
            if (isset($model->$field) && !empty($model->$field)) {
                return $model->$field;
            }
        }
        
        return null;
    }

    /**
     * Get model name in Turkish.
     */
    protected function getModelNameInTurkish(Model $model): string
    {
        $modelNames = [
            'App\\Models\\News' => 'haberi',
            'App\\Models\\Page' => 'sayfası',
            'App\\Models\\Service' => 'hizmeti',
            'App\\Models\\Event' => 'etkinliği',
            'App\\Models\\User' => 'kullanıcısı',
            'App\\Models\\Archive' => 'arşivi',
            'App\\Models\\ArchiveDocument' => 'arşiv belgesi',
            'App\\Models\\Project' => 'projesi',
            'App\\Models\\ProjectCategory' => 'proje kategorisi',
            'App\\Models\\Slider' => 'slider\'ı',
            'App\\Models\\NewsCategory' => 'haber kategorisi',
            'App\\Models\\ServiceCategory' => 'hizmet kategorisi',
            'App\\Models\\Mayor' => 'başkan bilgisi',
            'App\\Models\\MayorContent' => 'başkan içeriği',
            'App\\Models\\Setting' => 'ayarı',
            'App\\Models\\MenuSystem' => 'menü sistemi',
            'App\\Models\\MenuSystemItem' => 'menü öğesi',
            'App\\Models\\CorporateMember' => 'kurumsal üyesi',
            'App\\Models\\CorporateCategory' => 'kurumsal kategorisi',
            'App\\Models\\GuidePlace' => 'rehber yeri',
            'App\\Models\\GuideCategory' => 'rehber kategorisi',
            'App\\Models\\Mudurluk' => 'müdürlüğü',
            'App\\Models\\Announcement' => 'duyurusu',
            'App\\Models\\Tender' => 'ihalesi',
        ];
        
        $modelClass = get_class($model);
        return $modelNames[$modelClass] ?? 'kaydı';
    }

    /**
     * Get action name in Turkish.
     */
    protected function getActionInTurkish(string $action): string
    {
        $actions = [
            'created' => 'oluşturuldu',
            'updated' => 'güncellendi',
            'deleted' => 'silindi',
            'restored' => 'geri yüklendi',
            'force_deleted' => 'kalıcı olarak silindi',
        ];

        return $actions[$action] ?? $action;
    }

    /**
     * Get model attributes excluding sensitive fields.
     */
    protected function getModelAttributes($attributes): array
    {
        if (is_object($attributes)) {
            $attributes = $attributes->toArray();
        }

        if (!is_array($attributes)) {
            return [];
        }

        // Hassas alanları çıkar
        $filtered = array_diff_key($attributes, array_flip($this->excludedFields));
        
        // Model'e özel excluded fields varsa onları da çıkar
        if (method_exists($this, 'getExcludedFields')) {
            $modelExcluded = $this->getExcludedFields();
            $filtered = array_diff_key($filtered, array_flip($modelExcluded));
        }

        return $filtered;
    }

    /**
     * Get changed attributes between old and new values.
     */
    protected function getChangedAttributes(array $oldValues, array $newValues): array
    {
        $changedOld = [];
        $changedNew = [];

        foreach ($newValues as $key => $newValue) {
            $oldValue = $oldValues[$key] ?? null;
            
            // Değer değiştiyse logla
            if ($oldValue !== $newValue) {
                $changedOld[$key] = $oldValue;
                $changedNew[$key] = $newValue;
            }
        }

        return [
            'old' => $changedOld,
            'new' => $changedNew,
        ];
    }



    /**
     * Create custom activity log with description.
     */
    protected function createCustomLog(Model $model, string $action, string $description): void
    {
        $this->createActivityLog($model, $action, null, null, $description);
    }
} 