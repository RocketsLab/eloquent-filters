<?php
/**
 * Created by PhpStorm.
 * User: Jorge
 * Date: 08/07/2017
 * Time: 11:17
 */
namespace Duuany\EloquentFilters\Tests\Support;

use Duuany\EloquentFilters\Concerns\HasFilters;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFilters;

    protected $fillable = [
        'name',
        'email'
    ];

}