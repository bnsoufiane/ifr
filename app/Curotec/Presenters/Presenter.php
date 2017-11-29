<?php

namespace Curotec\Presenters;

use \Illuminate\Support\Collection;

class Presenter {
	public static function wrap($models, $options = array()) {
		$result = [];

		$models->each(\Closure::bind(function ($model) use (&$result, $options) {
			$result[] = new static($model, $options);
		}, null, get_called_class()));

		return $result;
	}
}
