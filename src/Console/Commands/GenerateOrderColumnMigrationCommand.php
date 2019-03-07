<?php

namespace Brackets\AdminSortable\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\File;

class GenerateOrderColumnMigrationCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:sortableMigration {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create migration for adding order_column to existing table';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Migration';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return file_get_contents(__DIR__ . '/Stubs/sortable-migration.stub');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Controllers\Admin';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }

    protected function migration($name)
    {
        $migrationTemplate = str_replace(
            [
                '{{fileName}}',
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}'
            ],
            [
                'AddOrderColumnTo'. ucfirst(str_plural($name)),
                ucfirst($name),
                strtolower(str_plural($name)),
                strtolower($name)
            ],
            $this->getStub('Migration')
        );

        $migrationFileName =  date('Y_m_d_His') . '_' . 'add_order_column_to_' . strtolower(str_plural($name)) .'.php';

        file_put_contents(base_path('database/migrations/'. $migrationFileName), $migrationTemplate);
    }

    public function handle()
    {
        $name = $this->argument('name');

        $this->migration($name);
    }
}