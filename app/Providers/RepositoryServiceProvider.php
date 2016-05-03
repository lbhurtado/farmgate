<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Repositories\ShortMessageRepository::class, \App\Repositories\ShortMessageRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\ContactRepository::class, \App\Repositories\ContactRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\GroupRepository::class, \App\Repositories\GroupRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\TokenRepository::class, \App\Repositories\TokenRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\ClusterRepository::class, \App\Repositories\ClusterRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\ElectivePositionRepository::class, \App\Repositories\ElectivePositionRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\CandidateRepository::class, \App\Repositories\CandidateRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\ElectionResultRepository::class, \App\Repositories\ElectionResultRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\TownRepository::class, \App\Repositories\TownRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\BarangayRepository::class, \App\Repositories\BarangayRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\PollingPlaceRepository::class, \App\Repositories\PollingPlaceRepositoryEloquent::class);
        //:end-bindings:
    }
}
