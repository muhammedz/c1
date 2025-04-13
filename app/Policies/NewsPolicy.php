<?php

namespace App\Policies;

use App\Models\News;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NewsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Tüm kullanıcılar haberleri listeleyebilir
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, News $news): bool
    {
        // Tüm kullanıcılar haberleri görüntüleyebilir
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Sadece admin rolündeki kullanıcılar haber oluşturabilir
        return $user->hasRole('admin') || $user->hasRole('editor');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, News $news): bool
    {
        // Sadece admin rolündeki kullanıcılar veya haberi oluşturan kullanıcı güncelleyebilir
        return $user->hasRole('admin') || $user->hasRole('editor') || $user->id === $news->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, News $news): bool
    {
        // Sadece admin rolündeki kullanıcılar haberi silebilir
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, News $news): bool
    {
        // Sadece admin rolündeki kullanıcılar haberi geri yükleyebilir
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, News $news): bool
    {
        // Sadece admin rolündeki kullanıcılar haberi kalıcı olarak silebilir
        return $user->hasRole('admin');
    }
    
    /**
     * Determine whether the user can toggle headline status.
     */
    public function toggleHeadline(User $user, News $news): bool
    {
        // Sadece admin veya editor rolündeki kullanıcılar manşet durumunu değiştirebilir
        return $user->hasRole('admin') || $user->hasRole('editor');
    }
    
    /**
     * Determine whether the user can toggle featured status.
     */
    public function toggleFeatured(User $user, News $news): bool
    {
        // Sadece admin veya editor rolündeki kullanıcılar öne çıkarma durumunu değiştirebilir
        return $user->hasRole('admin') || $user->hasRole('editor');
    }
    
    /**
     * Determine whether the user can toggle archive status.
     */
    public function toggleArchive(User $user, News $news): bool
    {
        // Sadece admin veya editor rolündeki kullanıcılar arşiv durumunu değiştirebilir
        return $user->hasRole('admin') || $user->hasRole('editor');
    }
    
    /**
     * Determine whether the user can toggle publication status.
     */
    public function toggleStatus(User $user, News $news): bool
    {
        // Sadece admin veya editor rolündeki kullanıcılar yayın durumunu değiştirebilir
        return $user->hasRole('admin') || $user->hasRole('editor');
    }
}
