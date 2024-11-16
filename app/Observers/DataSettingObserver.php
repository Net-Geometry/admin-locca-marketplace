<?php

namespace App\Observers;

use App\Models\DataSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class DataSettingObserver
{
    /**
     * Handle the DataSetting "created" event.
     */
    public function created(DataSetting $dataSetting): void
    {
        $this->refreshBusinessSettingsCache();
    }

    /**
     * Handle the DataSetting "updated" event.
     */
    public function updated(DataSetting $dataSetting): void
    {
        $this->refreshBusinessSettingsCache();
    }

    /**
     * Handle the DataSetting "deleted" event.
     */
    public function deleted(DataSetting $dataSetting): void
    {
        $this->refreshBusinessSettingsCache();
    }

    /**
     * Handle the DataSetting "restored" event.
     */
    public function restored(DataSetting $dataSetting): void
    {
        $this->refreshBusinessSettingsCache();
    }

    /**
     * Handle the DataSetting "force deleted" event.
     */
    public function forceDeleted(DataSetting $dataSetting): void
    {
        $this->refreshBusinessSettingsCache();
    }

    private function refreshBusinessSettingsCache()
    {
        Cache::forget('business_settings_flutter_landing_page');
    }
}
