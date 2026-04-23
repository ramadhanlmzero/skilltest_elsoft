<?php

namespace App\Providers;

use App\Models\PersonalAccessTokenModel;
use App\Repositories\Auth\AuthRepository;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Repositories\Item\ItemRepository;
use App\Repositories\Item\ItemRepositoryInterface;
use App\Repositories\StockIssue\StockIssueRepository;
use App\Repositories\StockIssue\StockIssueRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(ItemRepositoryInterface::class, ItemRepository::class);
        $this->app->bind(StockIssueRepositoryInterface::class, StockIssueRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessTokenModel::class);
    }
}
