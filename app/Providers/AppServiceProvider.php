<?php

namespace App\Providers;

use App\Adapters\ConfigAdapter;
use App\Repositories\ChatHistoryRepository;
use Illuminate\Support\ServiceProvider;
use Saas\Project\Dependencies\Config\ConfigInterface;
use Saas\Project\Modules\OpenAi\Chat\Creation\Gateways\SaveChatHistoryGateway;

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
        $this->app->bind(SaveChatHistoryGateway::class, ChatHistoryRepository::class);

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
