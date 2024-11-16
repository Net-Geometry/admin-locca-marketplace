<?php

namespace App\Observers;

use App\Models\Module;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class ModuleObserver
{
    /**
     * Handle the Module "created" event.
     */
    public function created(Module $module): void
    {
        $this->refreshBusinessSettingsCache();
    }

    /**
     * Handle the Module "updated" event.
     */
    public function updated(Module $module): void
    {
        $this->refreshBusinessSettingsCache();
    }

    /**
     * Handle the Module "deleted" event.
     */
    public function deleted(Module $module): void
    {
        $this->refreshBusinessSettingsCache();
    }

    /**
     * Handle the Module "restored" event.
     */
    public function restored(Module $module): void
    {
        $this->refreshBusinessSettingsCache();
    }

    /**
     * Handle the Module "force deleted" event.
     */
    public function forceDeleted(Module $module): void
    {
        $this->refreshBusinessSettingsCache();
    }

    private function refreshBusinessSettingsCache()
    {
        Cache::forget('active_module');
    }
}
