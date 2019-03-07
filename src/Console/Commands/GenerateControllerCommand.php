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
				ucfirst(str_singular($name)),
				ucfirst(str_plural($name)).'Sortable',
				strtolower(str_plural($name)),
				strtolower(str_singular($name))
			],
			$this->getStub('Controller')
		);

		file_put_contents(app_path("/Http/Controllers/Admin/".ucfirst($name)."SortableController.php"), $controllerTemplate);
	}

	public function handle()
	{
		$name = $this->argument('name');

		$this->controller($name);

		File::append(base_path('routes/web.php'),
			"
			Route::middleware(['admin'])->group(function () {
				Route::get('/admin/sort/". str_plural(strtolower($name)) ."', 'Admin\\". str_plural(ucfirst($name))."SortableController@index')->name('admin/". str_plural(strtolower($name)) . "/sort');
				Route::post('/admin/update-order/". str_plural(strtolower($name)) ."', 'Admin\\". str_plural(ucfirst($name))."SortableController@update')->name('admin/". str_plural(strtolower($name)) . "/sort/update');
			});
		");
	}
}