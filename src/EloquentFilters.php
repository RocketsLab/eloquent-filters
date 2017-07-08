<?php
/**
 * Created by PhpStorm.
 * User: Jorge
 * Date: 08/07/2017
 * Time: 11:55
 */

namespace Duuany\EloquentFilters;


use Exception;
use Illuminate\Http\Request;
use Duuany\EloquentFilters\Contracts\FiltersContract;
use Duuany\EloquentFilters\Exceptions\EloquentFiltersException;

/**
 * Class EloquentFilters.
 * @package Duuany\EloquentFilters
 */
abstract class EloquentFilters implements FiltersContract
{
    /**
     * @var Request, Builder
     */
    protected $request, $builder;

    /** @var array  */
    protected $filters = [];

    /** @var string  */
    protected $delimiter = ':';

    /**
     * EloquentFilters constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Calls and applies all filters to QueryBuilder.
     * @param $builder
     * @return mixed
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $params = preg_split("/[{$this->delimiter}]/", $value);
                call_user_func_array([$this, $filter], $params);
            }
        }

        return $this->builder;
    }

    /**
     * Get the valid filters.
     * @return array
     * @throws EloquentFiltersException
     */
    public function getFilters()
    {
        if(! count($this->filters)) {
            throw new EloquentFiltersException;
        }

        return $this->request->intersect($this->filters);
    }

    /**
     * Dinamically calls Eloquent QueryBuilder methods.
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        try {
            return call_user_func_array([$this->builder, $name], $arguments);
        }catch (Exception $e) {
            throw $e;
        }
    }
}