<?php

namespace Brodud\Amqp;

use Brodud\Amqp\Consumer;
use Brodud\Amqp\Publisher;
use Illuminate\Support\ServiceProvider;

class AmqpServiceProvider extends ServiceProvider
{
    
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('Amqp', 'Brodud\Amqp\Amqp');
        if (!class_exists('Amqp')) {
            class_alias('Brodud\Amqp\Facades\Amqp', 'Amqp');
        }

        $this->publishes([
            __DIR__.'/../config/amqp.php' => config_path('amqp.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Brodud\Amqp\Publisher', function ($app) {
            return new Publisher(config());
        });
        $this->app->singleton('Brodud\Amqp\Consumer', function ($app) {
            return new Consumer(config());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Amqp', 'Brodud\Amqp\Publisher', 'Brodud\Amqp\Consumer'];
    }
}
