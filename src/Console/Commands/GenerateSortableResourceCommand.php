<?php

namespace Brackets\AdminSortable\Console\Commands;

use Illuminate\Console\Command;

class GenerateSortableResourceCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'make:sortable {name}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'create sortable controller, migration, view';

	public function handle()
	{
		$name = $this->argument('name');

		$this->call('make:sortableController', ['name' => $name]);
		$this->call('make:sortableMigration', ['name' => $name]);
		$this->call('make:sortableListing', ['name' => $name]);
	}
}