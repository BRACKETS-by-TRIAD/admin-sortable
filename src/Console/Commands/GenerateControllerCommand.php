<?php

namespace Brackets\AdminSortable\Console\Commands;

use Brackets\AdminSortable\Traits\Columns;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateControllerCommand extends GeneratorCommand
{

    use Columns;

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
        return strtolower(str_replace('_', '-', str_plural($name)));
    }

    /**
     * @param String $name
     * @return array
     */
    public function getColumnsToQuery(String $name): array {
        $tableName = strtolower(str_plural($name));

        return $this->readColumnsFromTable($tableName)->filter(function($column) use ($tableName) {
            if($this->readColumnsFromTable($tableName)->contains('name', 'created_by_admin_user_id')){
                return !($column['type'] == 'text' || $column['name'] == "password" || $column['name'] == "remember_token" || $column['name'] == "slug" || $column['name'] == "updated_at" || $column['name'] == "deleted_at");
            } else if($this->readColumnsFromTable($tableName)->contains('name', 'updated_by_admin_user_id')) {
                return !($column['type'] == 'text' || $column['name'] == "password" || $column['name'] == "remember_token" || $column['name'] == "slug" || $column['name'] == "created_at" ||  $column['name'] == "deleted_at");
            } else if($this->readColumnsFromTable($tableName)->contains('name', 'created_by_admin_user_id') && $this->readColumnsFromTable($tableName)->contains('name', 'updated_by_admin_user_id')) {
                return !($column['type'] == 'text' || $column['name'] == "password" || $column['name'] == "remember_token" || $column['name'] == "slug" || $column['name'] == "deleted_at");
            }
            return !($column['type'] == 'text' || $column['name'] == "password" || $column['name'] == "remember_token" || $column['name'] == "slug" || $column['name'] == "created_at" || $column['name'] == "updated_at" || $column['name'] == "deleted_at");
        })->pluck('name')->toArray();
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
                '{{modelNameSingularLowerCase}}',
                '{{columnsToQuery}}'
            ],
            [
                $this->getSingularName($name),
                $this->getPluralName($name).'Sortable',
                $this->getRouteName($name),
                strtolower(str_replace('_', '-', str_singular($name))),
                json_encode($this->getColumnsToQuery($name)),
            ],
            $this->getStub('Controller')
        );

        file_put_contents(app_path("/Http/Controllers/Admin/".$this->getPluralName($name)."SortableController.php"), $controllerTemplate);

        $this->info(app_path("/Http/Controllers/Admin/".$this->getPluralName($name))."SortableController.php generated sucessfully");
    }

    /**
     *
     */
    public function handle()
    {
        $name = Str::snake($this->argument('name'));

        $this->controller($name);

        File::append(base_path('routes/web.php'),
            "
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        ". str_pad("Route::get('/sort/". $this->getRouteName($name) . "',", 60) . "'" . $this->getPluralName($name) ."SortableController@index')->name('". $this->getRouteName($name) . "/sort');" . "
        ". str_pad("Route::post('/update-order/". $this->getRouteName($name) . "',", 60) . "'" . $this->getPluralName($name) ."SortableController@update')->name('". $this->getRouteName($name) . "/sort/update');" . "
    });
});
            ");
    }
}
