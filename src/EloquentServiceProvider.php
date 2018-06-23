<?php

declare(strict_types=1);

/*
 * This file is part of Ark Eloquent.
 *
 * (c) ArkX <hello@arkx.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArkX\Eloquent;

use Illuminate\Support\ServiceProvider;

/**
 * This is the service provider class.
 *
 * @author Brian Faust <hello@brianfaust.me>
 */
class EloquentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/ark-eloquent.php' => config_path('ark-eloquent.php'),
        ]);
    }

    /**
     * Register any package services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ark-eloquent.php', 'ark-eloquent');
    }
}
