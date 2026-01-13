<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\AssetMaterialRepositoryInterface;
use App\Repositories\Contracts\AssetToolRepositoryInterface;
use App\Repositories\Contracts\AssetModelRepositoryInterface;
use App\Repositories\Contracts\AssetRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\AssetMaterialRepository;
use App\Repositories\AssetToolRepository;
use App\Repositories\AssetModelRepository;
use App\Repositories\AssetRepository;
use App\Repositories\RoleRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind Asset Material Repository
        $this->app->bind(AssetMaterialRepositoryInterface::class, AssetMaterialRepository::class);
        
        // Bind Asset Tool Repository
        $this->app->bind(AssetToolRepositoryInterface::class, AssetToolRepository::class);
        
        // Bind Asset Model Repository
        $this->app->bind(AssetModelRepositoryInterface::class, AssetModelRepository::class);
        
        // Bind Asset Repository
        $this->app->bind(AssetRepositoryInterface::class, AssetRepository::class);
        
        // Bind Role Repository
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
