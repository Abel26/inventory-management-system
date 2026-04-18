<?php

namespace App\Providers;

use App\Repositories\AssetMaterialRepository;
use App\Repositories\AssetModelRepository;
use App\Repositories\AssetRepository;
use App\Repositories\AssetToolRepository;
use App\Repositories\WorkLogRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\AssetMaterialRepositoryInterface;
use App\Repositories\Contracts\AssetToolRepositoryInterface;
use App\Repositories\Contracts\AssetModelRepositoryInterface;
use App\Repositories\Contracts\AssetRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\ReportRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\WorkLogRepositoryInterface;
use App\Repositories\ReportRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Services\UserService;
use App\Services\RoleService;
use App\Services\WorkLogService;

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
        
        // Bind Work Log Repository and Service
        $this->app->bind(WorkLogRepositoryInterface::class, WorkLogRepository::class);
        $this->app->singleton(WorkLogService::class, function ($app) {
            return new WorkLogService($app->make(WorkLogRepositoryInterface::class));
        });
        
        // Bind Role Repository and Service
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->singleton(RoleService::class, function ($app) {
            return new RoleService($app->make(RoleRepositoryInterface::class));
        });
        
        // Bind User Repository and Service
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->singleton(UserService::class, function ($app) {
            return new UserService($app->make(UserRepositoryInterface::class));
        });
        
        // Bind Report Repository
        $this->app->bind(ReportRepositoryInterface::class, ReportRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
