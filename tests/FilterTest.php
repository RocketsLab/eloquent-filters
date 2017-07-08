<?php
/**
 * Created by PhpStorm.
 * User: Jorge
 * Date: 08/07/2017
 * Time: 11:14
 */

namespace Duuany\EloquentFilters\Tests;


use Mockery;
use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase;
use Duuany\EloquentFilters\Tests\Support\User;
use Duuany\EloquentFilters\Tests\Support\UserFilters;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Duuany\EloquentFilters\Tests\Support\AnotherFilters;
use Duuany\EloquentFilters\Exceptions\EloquentFiltersException;

class FilterTest extends TestCase
{
    use DatabaseMigrations;

    protected $request;

    protected function setUp()
    {
        parent::setUp();

        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--realpath' => realpath(__DIR__ . '/Support/migration')
        ]);

        User::create([
            'name'  => 'JoaoDor',
            'email' => 'joaodor@exemplo.br'
        ]);

        User::create([
            'name'  => 'MariaDor',
            'email' => 'mariador@exemplo.br'
        ]);

    }

    protected function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connectons.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => ''
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            \Orchestra\Database\ConsoleServiceProvider::class,
            \Duuany\EloquentFilters\EloquentFiltersServiceProvider::class
        ];
    }

    /** @test */
    public function it_can_get_filters()
    {
        $this->buildRequest(['order'], ['order' => 'id']);

        $filters = new UserFilters($this->request);

        $this->assertEquals(1, count($filters->getFilters()));
    }

    /** @test */
    public function it_applies_filters_to_query()
    {
        $this->buildRequest(['order'], ['order' => 'id']);

        $filters = new UserFilters($this->request);

        $user = User::filter($filters)->first();

        $this->assertEquals('JoaoDor', $user->name);
    }

    /** @test */
    public function it_applies_filters_to_query_with_multiple_parameters()
    {
        $this->buildRequest(['order'], ['order' => 'id|desc']);

        $filters = new UserFilters($this->request);

        $user = User::filter($filters)->first();

        $this->assertEquals('MariaDor', $user->name);
    }

    /** @test */
    public function it_throws_exception_when_no_filters_defined()
    {
        $this->buildRequest(null, null);

        $filters = new AnotherFilters($this->request);

        $this->expectException(EloquentFiltersException::class);

        User::filter($filters);
    }

    protected function buildRequest($queryStrings, $requestBody)
    {
        $this->request = Mockery::mock(Request::class)
                      ->shouldReceive('intersect')
                      ->with($queryStrings)
                      ->andReturn($requestBody)->getMock();
    }
}