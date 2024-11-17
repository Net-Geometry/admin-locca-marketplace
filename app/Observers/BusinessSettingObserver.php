<?php

namespace App\Observers;

use App\Models\BusinessSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class BusinessSettingObserver
{
    /**
     * Handle the BusinessSetting "created" event.
     */
    public function created(BusinessSetting $businessSetting): void
    {
        $this->refreshBusinessSettingsCache();
    }

    /**
     * Handle the BusinessSetting "updated" event.
     */
    public function updated(BusinessSetting $businessSetting): void
    {
        $this->refreshBusinessSettingsCache();
    }

    /**
     * Handle the BusinessSetting "deleted" event.
     */
    public function deleted(BusinessSetting $businessSetting): void
    {
        $this->refreshBusinessSettingsCache();
    }

    /**
     * Handle the BusinessSetting "restored" event.
     */
    public function restored(BusinessSetting $businessSetting): void
    {
        $this->refreshBusinessSettingsCache();
    }

    /**
     * Handle the BusinessSetting "force deleted" event.
     */
    public function forceDeleted(BusinessSetting $businessSetting): void
    {
        $this->refreshBusinessSettingsCache();
    }

    private function refreshBusinessSettingsCache()
    {
        $prefix = 'business_settings_';
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
