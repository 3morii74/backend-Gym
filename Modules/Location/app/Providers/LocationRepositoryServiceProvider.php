<?php

namespace Modules\Location\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Location\Repositories\interface\LocationRepositoryInterface;
use Modules\Location\Repositories\LocationRepository;

class LocationRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Bind the interface to the repository
        $this->app->bind(LocationRepositoryInterface::class, LocationRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // You can add additional boot logic if needed
    }
}
