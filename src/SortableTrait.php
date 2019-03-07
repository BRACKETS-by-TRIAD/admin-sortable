<?php

namespace Brackets\AdminSortable;

use Brackets\Sortable\SortableScope;

trait Sortable
{	
	public static function bootSortable()
	{
		static::addGlobalScope(new SortableScope);
	}
	
}