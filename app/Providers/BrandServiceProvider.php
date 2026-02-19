<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Contracts\View\Factory as ViewFactory;

class BrandServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // Register the component namespace
        $this->app->singleton('brand', function ($app) {
            return new BrandComponentManager($app['view']);
        });
        
        // Register Blade component alias
        $this->app->singleton('blade.compiler', function ($app) {
            return new \Illuminate\View\Compilers\BladeCompiler($app['view'], $app['config'], $app['events']);
        });
    }
    
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Register the component in the container
        $this->app->singleton('brand.ebara-logo', function () {
            return new \Illuminate\View\Compilers\ComponentTagCompiler('ebara-logo');
        });
    }
}

/**
 * Class for managing brand components.
 */
class BrandComponentManager
{
    protected $view;
    
    public function __construct(ViewFactory $view)
    {
        $this->view = $view;
    }
    
    /**
     * Register a component.
     */
    public function register(string $name, string $class)
    {
        $this->view->component($name, $class);
    }
    
    /**
     * Get a component instance.
     */
    public function make(string $name, array $data = [])
    {
        return $this->view->make($name, $data);
    }
}