<?php

namespace App\Providers;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\TranslationRepositoryInterface;
use App\Repositories\CategoryRepository;
use App\Repositories\TranslationRepository;
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
        ];
        foreach ($array as $item) {
            $this->app->bind($item['key'], $item['value']);
        }

    }
}
