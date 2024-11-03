<?php

namespace App\Providers;

use App\Adapters\ConfigAdapter;
use Illuminate\Support\ServiceProvider;
use Saas\Project\Dependencies\Config\ConfigInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ConfigInterface::class, ConfigAdapter::class);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
