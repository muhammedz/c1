<?php

namespace App\Observers;

use App\Models\User;
use App\Traits\LogsActivity;

class UserObserver
{
    use LogsActivity;

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->logCreated($user);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $this->logUpdated($user);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->logDeleted($user);
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        $this->logRestored($user);
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        $this->logForceDeleted($user);
    }

    /**
     * User modeli için hariç tutulacak hassas alanlar
     */
    protected function getExcludedFields(): array
    {
        return [
            'password',
            'remember_token',
            'email_verified_at',
            'two_factor_secret',
            'two_factor_recovery_codes',
        ];
    }
}
