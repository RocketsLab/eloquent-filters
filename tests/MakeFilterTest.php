<?php
/**
 * Created by PhpStorm.
 * User: Jorge
 * Date: 08/07/2017
 * Time: 14:07
 */

namespace Duuany\EloquentFilters\Tests;

use Orchestra\Testbench\TestCase;

class MakeFilterTest extends TestCase
{
    protected $filterName = 'TestFilter';

    protected $fileName;

    protected function setUp()
    {
        parent::setUp();

        $this->fileName = app_path('/Filters/'.$this->filterName.'.php');

    }

    protected function tearDown()
    {
        parent::tearDown();

        if(file_exists($this->fileName)) {
            unlink($this->fileName);
        }
    }

    protected function getPackageProviders($app)
    {
        return [
            \Duuany\EloquentFilters\EloquentFiltersServiceProvider::class
        ];
    }

    /** @test */
    public function creates_a_new_filter()
    {
        $this->artisan('make:filter', ['filter' => 'TestFilter']);

        $this->assertFileExists($this->fileName);
    }
}