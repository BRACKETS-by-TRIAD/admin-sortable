<?php namespace Brackets\AdminSortable;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Brackets\AdminSortable\Console\Commands\GenerateControllerCommand;
use Brackets\AdminSortable\Console\Commands\GenerateSortableResourceCommand;
use Brackets\AdminSortable\Console\Commands\GenerateListingCommand;
use Brackets\AdminSortable\Console\Commands\GenerateOrderColumnMigrationCommand;

class SortableServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		if ($this->app->runningInConsole()) {
			$this->commands([
				GenerateSortableResourceCommand::class,
				GenerateOrderColumnMigrationCommand::class,
				GenerateControllerCommand::class,
				GenerateListingCommand::class,
			]);
		};

		$this->publishes([
			__DIR__ . '/../resources/assets' =>
				resource_path('assets/admin/js/vendor/sortable')
		], 'vue-components');

		Builder::macro('updateOrder', function (array $sortedArray) {
			foreach ($sortedArray as $key => $model) {
				$this->where('id', $model['id'])->update(['order_column' => $key + 1]);
			}
			return $this;
		});
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{

	}
}