<?php
/**
 * Created by PhpStorm.
 * User: Jorge
 * Date: 08/07/2017
 * Time: 13:39
 */

namespace Duuany\EloquentFilters\Contracts;

/**
 * Interface FiltersContract
 * @package Duuany\EloquentFilters\Contracts
 */
interface FiltersContract
{
    /**
     * Calls and applies all filters to QueryBuilder.
     * @param $builder
     * @return mixed
     */
    public function apply($builder);

    /**
     * Get the valid filters.
     * @return array
     * @throws \Duuany\EloquentFilters\Exceptions\EloquentFiltersException
     */
    public function getFilters();

    /**
     * Dinamically calls Eloquent QueryBuilder methods.
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, $arguments);

}