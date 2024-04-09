<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Validator::extend('validate_ids_exist', function ($attribute, $value, $parameters, $validator) {
            $modelClass = "App\\Models\\" . ucfirst($parameters[0]);

            // Verificar si los IDs con status = 1 existen en la tabla correspondiente
            $valid = $modelClass::where('status', '1')->whereIn('id', explode(',', $value))->count() == count(explode(',', $value));

            return $valid;
        }, ":attribute seleccionado(a) no válido.");
    }
}
