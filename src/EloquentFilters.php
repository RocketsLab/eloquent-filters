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
     * @var \Illuminate\Http\Request $request
     * @var \Illuminate\Database\Eloquent\Builder $builder
     */
    protected $request, $builder;

    /** @var array */
    protected $filters = [];

    /** @var string */
    protected $delimiter = ':';

    /**
     * EloquentFilters constructor.
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Calls and applies all filters to QueryBuilder.
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return mixed
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            $params = preg_split("/[{$this->delimiter}]/", $value);
            if (method_exists($this, $filter) && count($params) > 1) {
                call_user_func_array([$this, $filter], $params);
            } else {
                $this->builder->when($value ?? null, function () use ($filter, $value) {
                    return $this->$filter($value);
                });
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
        if (!count($this->filters)) {
            throw new EloquentFiltersException;
        }

        return collect($this->request->all())->filter(function ($_, $key) {
            return collect($this->filters)->contains($key);
        })->toArray();
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
        } catch (Exception $e) {
            throw $e;
        }
    }
}