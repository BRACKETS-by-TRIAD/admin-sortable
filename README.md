# Admin Sortable
Admin Sortable is a Laravel package for Craftable used to add sortable feature for you Eloquent models.

## Requirements
Admin Sortable requires:
1. php 7.1+

## Installation

### Steps

1. Run `composer require brackets/admin-sortable` in your Craftable project folder.
2. Run `npm install @shopify/draggable@1.0.0-beta.8` in your Craftable project folder.
3. Run `php artisan vendor:publish --provider="Brackets\AdminSortable\SortableServiceProvider" --tag="vue-components"` for publishing vue components and assets.
4. Add `require('./vendor/sortable/app');` to your `resources/js/admin/admin.js` file
5. Compile assets `npm run dev`

## Add sortable feature for existing model
1. Run `php artisan make:sortable ModelName` (this will generate migration, controller and sortable listing, if you want to generate this files separately follow the steps in [Generate sortable files separately](#generate-sortable-files-separately) section )
2. Run `php artisan migrate`
3. Add following button to resources/views/admin/model-name/index.blade.php file on place where you want to have Sort items button
```php
    <a href="{{ route('admin/model-name/sort') }}" class="btn btn-primary">Sort items</a>          
```
4. Compile assets `npm run dev`

<a name="generate-sortable-files-separately"></a>
## Generate sortable files separately
1. Run `php artisan make:sortableMigration ModelName` for generating sortable migration
2. Run `php artisan make:sortableController ModelName` for generating sortable controller
3. Run `php artisan make:sortableListing ModelName` for generating sortable listing

## Issues
Where do I report issues?
If something is not working as expected, please open an issue in the main repository https://github.com/BRACKETS-by-TRIAD/craftable.