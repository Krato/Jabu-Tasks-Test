<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::macro('fullSql', function () {
            $sql = str_replace(['%', '?'], ['%%', '%s'], $this->toSql());

            $handledBindings = array_map(function ($binding) {
                if (is_numeric($binding)) {
                    return $binding;
                }

                $value = str_replace(['\\', "'"], ['\\\\', "\'"], $binding);

                return "'{$value}'";
            }, $this->getConnection()->prepareBindings($this->getBindings()));

            $fullSql = vsprintf($sql, $handledBindings);

            return $fullSql;
        });

        Builder::macro('ddd', function () {
            dd($this->fullSql());
        });
    }
}
