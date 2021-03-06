<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        
        //Adding a new validator rule that compares numerical values to be greater than another
        Validator::extend('greater_than', function($attribute, $value, $params, $validator){
            $other = Input::get($params[0]);
            return intval($value) > intval($other);
        });

        //Adding a new validator rule that allow spaces
        Validator::extend('alpha_spaces', function($attribute, $value, $params, $validator){
            return preg_match('/^[\pL\s]+$/u', $value);
        });

        //Adding a new validator rule that matches exactly the license type: XXX000 or XX0000
        Validator::extend('license_type', function($attribute, $value, $params, $validator){
            return preg_match('/^[a-zA-Z]{2}\d{4}|[a-zA-Z]{3}\d{3}$/u', $value);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
