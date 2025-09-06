<?php

namespace App\Providers;

use App\Models\ProductVariant;
use App\Repositories\CartRepo\CartRepository;
use App\Repositories\CartRepo\ICartRepository;
use App\Repositories\CategoryRepo\CategoryRepository;
use App\Repositories\CategoryRepo\ICategoryRepository;
use App\Repositories\ChatRepo\ChatRepository;
use App\Repositories\ChatRepo\IChatRepository;
use App\Repositories\ImageRepo\IImageRepository;
use App\Repositories\ImageRepo\ImageRepository;
use App\Repositories\OrderRepo\IOrderRepository;
use App\Repositories\OrderRepo\OrderRepository;
use App\Repositories\ProductRepo\IProductRepository;
use App\Repositories\ProductRepo\ProductRepository;
use App\Repositories\ProductVariantRepo\IProductVariantRepository;
use App\Repositories\ProductVariantRepo\ProductVariantRepository;
use App\Repositories\ResetpasswordRepo\IResetpasswordRepository;
use App\Repositories\ResetpasswordRepo\ResetpasswordRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepo\IUserRepository;
use App\Repositories\UserRepo\UserRepository;
use App\Services\ResetpasswordService;
use Illuminate\Contracts\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IResetpasswordRepository::class, ResetpasswordRepository::class);
        $this->app->bind(ICategoryRepository::class, CategoryRepository::class);
        $this->app->bind(IProductRepository::class, ProductRepository::class);
        $this->app->bind(IProductVariantRepository::class, ProductVariantRepository::class);
        $this->app->bind(IImageRepository::class, ImageRepository::class);
        $this->app->bind(ICartRepository::class, CartRepository::class);
        $this->app->bind(IOrderRepository::class, OrderRepository::class);
        $this->app->bind(IChatRepository::class, ChatRepository::class);
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
