<?php

namespace App\Providers;

use App\Repositories\Interfaces\SellersRepositoryInterface;
use App\Repositories\SellersRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            SellersRepositoryInterface::class,
            SellersRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
