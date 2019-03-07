<?php

namespace Brackets\AdminSortable\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\File;

class GenerateListingCommand extends GeneratorCommand
{

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $name = 'make:sortableListing {name}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a custom Sortable Listing.';

	/**
	 * The type of class being generated.
	 *
	 * @var string
	 */
	protected $type = 'View';

	/**
	 * Get the stub file for the generator.
	 *
	 * @return string
	 */
	protected function getStub()
	{
		return file_get_contents(__DIR__ . '/Stubs/sortable-listing.stub');
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

	protected function view($name)
	{
		$listingTemplate = str_replace(
			[
				'{{modelNamePluralLowerCase}}',
			],
			[
				strtolower(str_plural($name)),
			],
			$this->getStub('View')
		);

		file_put_contents(resource_path("views/admin/".str_singular($name)."/sortable-listing.blade.php"), $listingTemplate);
	}

	public function handle()
	{
		$name = $this->argument('name');

		$this->view($name);
	}
}