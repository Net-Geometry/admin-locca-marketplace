<?php

namespace App\Observers;

use App\Models\BusinessSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

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
        $cacheKeys = [
            'cache_business_table_data',
            'business_settings_keys',
            'business_settings_logo_storage',
            'business_settings_icon_storage',
            'business_settings_web_app_landing_page_settings_storage',
            'currency_symbol'
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }
}
