<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

/**
 * Class ValidatorServiceProvider
 * @package App\Providers
 */
class ValidatorServiceProvider extends ServiceProvider
{

    public function boot()
    {
        Validator::extend('asset_exists', 'App\Validation\AssetExistsValidator@validate');
    }

    public function register()
    {
        //
    }
}
