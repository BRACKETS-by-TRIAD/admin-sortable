<?php

namespace Brackets\AdminSortable\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\File;

class GenerateControllerCommand extends GeneratorCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:sortableController {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a custom Sortable Controller.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return file_get_contents(__DIR__ . '/Stubs/sortable-controller.stub');
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

    /**
     * @param String $name
     * @return String
     */
    public function getSingularName(String $name) : String{
        return collect(explode('_',str_singular($name)))->map(static function ($word){
            return ucfirst($word);
        })->implode('');
    }

    /**
     * @param String $name
     * @return String
     */
    public function getPluralName(String $name) : String{
        return collect(explode('_',str_plural($name)))->map(static function ($word){
            return ucfirst($word);
        })->implode('');
    }

    /**
     * @param String $name
     * @return String
     */
    public function getRouteName(String $name) : String{
        return strtolower(str_replace('_', '-', $name));
    }

    /**
     * @param $name
     */
    protected function controller($name)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{className}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}'
            ],
            [
                $this->getSingularName($name),
                $this->getPluralName($name).'Sortable',
                $this->getRouteName($name),
                strtolower($this->getSingularName($name))
            ],
            $this->getStub('Controller')
        );

        file_put_contents(app_path("/Http/Controllers/Admin/".$this->getPluralName($name)."SortableController.php"), $controllerTemplate);
    }

    /**
     *
     */
    public function handle()
    {
        $name = $this->argument('name');

        $this->controller($name);

        File::append(base_path('routes/web.php'),
            "
			Route::middleware(['admin'])->group(function () {
				Route::get('/admin/sort/". $this->getRouteName($name) ."', 'Admin\\". $this->getPluralName($name) ."SortableController@index')->name('admin/". $this->getRouteName($name) . "/sort');
				Route::post('/admin/update-order/". $this->getRouteName($name) ."', 'Admin\\". $this->getPluralName($name) ."SortableController@update')->name('admin/". $this->getRouteName($name) . "/sort/update');
			});
		");
    }
}
