<?php

namespace Brackets\Sortable;

use Brackets\Sortable\SortableScope;

trait Sortable
{	
	public static function bootSortable()
	{
		static::addGlobalScope(new SortableScope);
	}
	
}