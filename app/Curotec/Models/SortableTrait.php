<?php

namespace Curotec\Models;

trait SortableTrait {
	protected static $orderColumn = 'order';

	public static function bootSortableTrait() {
		static::creating(function ($model) {
			$property = static::$orderColumn;

			// Automatically add 'order' column value if it doesn't exists.
			if (!isset($model->{$property})) {
				$model->{$property} = static::max($property) + 1;
			}
		});
	}

	public function scopeSorted($query) {
		return $query->orderBy(static::$orderColumn);
	}
}
