<?php

namespace Brackets\AdminSortable;

use Brackets\AdminSortable\SortableScope;

trait Sortable
{	
	public static function bootSortable()
	{
		static::addGlobalScope(new SortableScope);
	}
	
}