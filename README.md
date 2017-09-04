# Eloquent Filters

##### A simplest Eloquent Model URL query filter package for your Laravel

## Instalation

You can install via composer:

### Laravel 5.5+
```bash
$ composer require duuany/eloquent-filters
```

###Laravel 5.4 or above
```bash
$ composer require duuany/eloquent-filters:1.1
```

Add the service provider to your config/app.php

````php
Duuany\EloquentFilters\EloquentFiltersServiceProvider::class
````

Optionally, you can publish config file to override package configuration

```bash
$ php artisan vendor:publish --provider="Duuany\EloquentFilters\EloquentFiltersServiceProvider" --tag="config"
```

## Usage

In your model, add the following HasFilters trait

```php
class User extends Model 
{
    use HasFilters;
}
```

Create a UserFilter class anywhere in your app folder. Filter classes defines array of applicable filters.
For each filter added to array of filters, you need to implement the filter logic.

You can create filters via artisan command

```bash
$ php artisan make:filter FilterName
```

```php
class UserFilter extends EloquentFilters
{
    protected $filters = [
        'order', 'popular', ....
    ]; 
    
    protected function order($column)
    {
        return $this->builder->orderBy($column, 'desc');
    }
}
```

You can pass multiple parameters to your filters, like this:

```php
    protected function order($column, $sort = 'desc')
    {
        return $this->builder->orderBy($column, $sort);
    }
```

When passing more than one paramenter to filter, make sure use a delimiter in your query string.

```
http://myurl.dev?order=id:asc
```

The default delimiter its `:`, but your can modify overriding the `protected $delimiter` property.
 
##### Example:

```php
class UserFilter extends EloquentFilters
{
    protected $delimiter = '|';
    
    ...
}
```

In query string...

```
http://myurl.dev?order=id|asc
```

... keep in mind, to not use special query strings characters like delimiters.

##### Magic Calls

Optionally, you can ommit `->builder`:
 
```php
// this...
return $this->builder->orderBy($column, $sort);

// can be called like this...
return $this->orderBy($column, $sort);
```

Now in your application you can use the filters as the following:

```php
class UserController extends Controller
{
    public function index(UserFilter $filters)
    {
        $users = User::filter($filters)->get();
    }
}
```

When the user access the URL http://localhost/?order=id, the order filter will be applied on the User model.

## Testing

```bash
$ ./vendor/bin/phpunit
```

## Credits
* [Jeffrey Way](http://laracasts.com)
* [Jorge Junior aka jjquady](http://github.com/jjsquady)
* Many coffee cups :D

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.