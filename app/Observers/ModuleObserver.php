<?php

namespace App\Observers;

use App\Models\Module;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
        $prefix = 'module_';
        $cacheKeys = DB::table('cache')
            ->where('key', 'like', "%" . $prefix . "%")
            ->pluck('key');
        $sanitizedKeys = $cacheKeys->map(function ($key) {
            $key = str_replace('laravel_cache', '', $key);
            return $key;
        });
        foreach ($sanitizedKeys as $key) {
            Cache::forget($key);
        }
    }
}
