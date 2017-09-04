<?php
/**
 * Created by PhpStorm.
 * User: Jorge
 * Date: 08/07/2017
 * Time: 13:45
 */

namespace Duuany\EloquentFilters\Console;


use Illuminate\Console\Command;
use Illuminate\Foundation\Application;

class MakeFilterCommand extends Command
{
    /**
     * Command signature.
     *
     * @var string
     */
    protected $signature = 'make:filter {filter}';
    /**
     * Command description.
     *
     * @var string
     */
    protected $description = 'Create a new model filter class';
    /**
     * Laravel app instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /** @var  string */
    protected $path;

    /** @var  string */
    protected $namespace;

    /** @var  string */
    protected $stub;

    /** @var  string */
    protected $filter;

    /**
     * MakeFilterCommand constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct();
        $this->app = $app;
    }

    /**
     * Execute console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->path      = $this->app['config']->get('filters.path');
        $this->namespace = $this->app['config']->get('filters.namespace');
        $this->filter    = $this->argument('filter');
        $this->stub      = $this->app['files']->get(realpath(__DIR__ . '/stubs/filter.stub'));

        $this->checkIfFilterExists();

        $this->checkForFiltersFolders();

        $this->createFilter();
    }

    protected function checkForFiltersFolders()
    {
        if (!$this->app['files']->exists($this->path)) {
            $this->app['files']->makeDirectory($this->path, 0755, true);
        }
    }

    protected function createFilter()
    {
        $stub = str_replace(['$NAMESPACE$', '$FILTER_NAME$'],
            [$this->namespace, $this->filter], $this->stub);
        $this->app['files']->put($this->path . '/' . $this->filter . '.php', $stub);
        $this->info("Filter $this->filter is created successfully!");
    }

    protected function checkIfFilterExists()
    {
        if ($this->app['files']->exists($this->path . '/' . $this->filter . '.php')) {
            $this->info("Filter $this->filter is already exists!");
            exit;
        }
    }
}