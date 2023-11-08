<?php

namespace App\Providers;

use App\Contracts\Repositories\AddonRepositoryInterface;
use App\Contracts\Repositories\AttributeRepositoryInterface;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\StoreRepositoryInterface;
use App\Contracts\Repositories\TranslationRepositoryInterface;
use App\Contracts\Repositories\UnitRepositoryInterface;
use App\Contracts\Repositories\ZoneRepositoryInterface;
use App\Repositories\AddonRepository;
use App\Repositories\AttributeRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\StoreRepository;
use App\Repositories\TranslationRepository;
use App\Repositories\UnitRepository;
use App\Repositories\ZoneRepository;
use Illuminate\Support\ServiceProvider;

class InterfaceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $array = [
            ['key' => CategoryRepositoryInterface::class, 'value' => CategoryRepository::class],
            ['key' => TranslationRepositoryInterface::class, 'value' => TranslationRepository::class],
            ['key' => AttributeRepositoryInterface::class, 'value' => AttributeRepository::class],
            ['key' => UnitRepositoryInterface::class, 'value' => UnitRepository::class],
            ['key' => AddonRepositoryInterface::class, 'value' => AddonRepository::class],
            ['key' => StoreRepositoryInterface::class, 'value' => StoreRepository::class],
            ['key' => ZoneRepositoryInterface::class, 'value' => ZoneRepository::class],
        ];
        foreach ($array as $item) {
            $this->app->bind($item['key'], $item['value']);
        }

    }
}
