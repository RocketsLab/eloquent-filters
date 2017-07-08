<?php
/**
 * Created by PhpStorm.
 * User: Jorge
 * Date: 08/07/2017
 * Time: 11:54
 */

namespace Duuany\EloquentFilters\Tests\Support;


use Duuany\EloquentFilters\EloquentFilters;

class UserFilters extends EloquentFilters
{
    protected $filters = [
        'order'
    ];

    protected $delimiter = '|';

    protected function order($column, $sort = 'asc')
    {
        return $this->orderBy($column, $sort);
    }
}