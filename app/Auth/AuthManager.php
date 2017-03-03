<?php

namespace App\Auth;

use Facebook\FacebookSession;
use Illuminate\Support\Facades\Config;
use Illuminate;

/**
 * Class AuthManager
 * @package App\Auth
 */
class AuthManager extends Illuminate\Auth\AuthManager
{
    /**
     * Create a new manager instance.
     * AuthManager constructor.
     * @param Illuminate\Foundation\Application $app
     */
    public function __construct($app)
    {
        parent::__construct($app);

        FacebookSession::setDefaultApplication(
            Config::get('auth.providers.facebook.app_id'),
            Config::get('auth.providers.facebook.app_secret')
        );
    }

    /**
     * @inheritdoc
     */
    public function createEloquentDriver()
    {
        $provider = $this->createEloquentProvider((array)config());

        return new Guard($provider, $this->app['session.store']);
    }
}
