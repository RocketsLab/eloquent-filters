<?php
/**
 * Created by PhpStorm.
 * User: Jorge
 * Date: 08/07/2017
 * Time: 11:04
 */

namespace Duuany\EloquentFilters;


use Illuminate\Support\ServiceProvider;
use Duuany\EloquentFilters\Console\MakeFilterCommand;

class EloquentFiltersServiceProvider extends ServiceProvider
{
    /**
     * Register package services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();

        $this->registerCommands();
    }

    /**
     * Register package configs.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $path = realpath(__DIR__ . '/../config/filters.php');
        $this->mergeConfigFrom($path, 'filters');
        $this->publishes([$path => config_path('filters.php')], 'config');
    }

    /**
     * Register package console commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->commands([
            MakeFilterCommand::class,
        ]);
    }
}