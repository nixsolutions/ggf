<?php

namespace App\Auth;

use Illuminate\Auth\AuthServiceProvider as ServiceProvider;

/**
 * Class AuthServiceProvider
 * @package App\Auth
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * @inheritdoc
     */
    protected function registerAuthenticator()
    {
        $this->app->bind('auth', function ($app) {
            // Once the authentication service has actually been requested by the developer
            // we will set a variable in the application indicating such. This helps us
            // know that we need to set any queued cookies in the after event later.
            $app['auth.loaded'] = true;
            return new AuthManager($app);
        });
        $this->app->singleton('auth.driver', function ($app) {
//            return $app['auth']->driver();
            return $app['auth']->guard();
        });
    }
}
