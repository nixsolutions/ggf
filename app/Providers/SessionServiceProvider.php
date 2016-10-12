<?php

namespace App\Providers;

use Illuminate\Session\SessionServiceProvider as ISessionServiceProvider;

/**
 * Class SessionServiceProvider
 * @package App\Providers
 */
class SessionServiceProvider extends ISessionServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSessionManager();

        $this->registerSessionDriver();

        $this->app->singleton('App\Http\Middleware\StartSession');
    }
}
