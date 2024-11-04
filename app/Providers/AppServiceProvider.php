<?php

namespace App\Providers;

use App\Adapters\ConfigAdapter;
use App\Adapters\RedisCacheAdapter;
use App\Repositories\ChatHistoryRepository;
use Illuminate\Support\ServiceProvider;
use Saas\Project\Dependencies\Cache\CacheInterface;
use Saas\Project\Dependencies\Config\ConfigInterface;
use Saas\Project\Modules\OpenAi\Chat\Creation\Gateways\SaveChatHistoryGateway;
use Saas\Project\Modules\OpenAi\Chat\Delete\Gateways\DeleteChatHistoryGateway;
use Saas\Project\Modules\OpenAi\Chat\Find\Gateways\RetrieveChatHistoryGateway;
use Saas\Project\Modules\OpenAi\Chat\Update\Gateways\UpdateChatHistoryGateway;

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
        $this->app->bind(DeleteChatHistoryGateway::class, ChatHistoryRepository::class);
        $this->app->bind(UpdateChatHistoryGateway::class, ChatHistoryRepository::class);
        $this->app->bind(RetrieveChatHistoryGateway::class, ChatHistoryRepository::class);
        $this->app->bind(CacheInterface::class, RedisCacheAdapter::class);

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
