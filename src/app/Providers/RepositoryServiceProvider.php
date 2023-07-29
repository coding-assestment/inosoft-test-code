<?php

namespace App\Providers;

use App\Interfaces\VehicleDtoInterface;
use App\Interfaces\VehicleRepositoryInterface;
use App\Interfaces\VehicleTransactionDtoInterface;
use App\Repositories\Dto\VehicleDto;
use App\Repositories\Dto\VehicleTransactionDto;
use App\Repositories\VehicleRepository;
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
        $this->app->bind(VehicleRepositoryInterface::class, VehicleRepository::class);
        $this->app->bind(VehicleDtoInterface::class, VehicleDto::class);
        $this->app->bind(VehicleTransactionDtoInterface::class, VehicleTransactionDto::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
