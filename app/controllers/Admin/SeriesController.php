<?php


namespace Admin;

use View;
use Input;
use Redirect;

use Module;
use Series;

class SeriesController extends BaseController {
	public function __construct() {
		$this->beforeFilter(function () {
			// Check if a user has cancelled editing.
			if (Input::has('cancel')) {
				return Redirect::route('admin.modules.index');
			}
		}, array('only' => array('store', 'update')));
	}

	public function create($moduleId) {
		$module = Module::find($moduleId);

		if (!$module) {
			return Redirect::route('admin.modules.index')
				->with('error', 'Can\'t add series to a non-existing product.');
		}

		$view = View::make('admin/series/create')
			->with('series', new Series())
			->with('module', $module)
			->with('route', array('admin.series.store'));

		$this->layout->content = $view;
	}

	public function edit($id) {
		$series = \Series::find($id);

		if (!$series) {
			return Redirect::route('admin.modules.index')
				->with('error', 'Series not found.');
		}

		$view = View::make('admin/series/edit')
			->with('series', $series)
			->with('module', $series->module)
			->with('route', array('admin.series.update', $series->id))
			->with('method', 'PUT');

		$this->layout->content = $view;
	}

	/**
	 * Updates a module with new data.
	 *
	 * @param int $id
	 * @return mixed
	 */
	public function update($id) {
		$input = Input::all();
		$validator = \Series::validate($input, $id);

		if ($validator->fails()) {
			return Redirect::route('admin.series.edit', $id)
				->withInput()
				->withErrors($validator);
		} else {
			// Updating the module
			$series = Series::find($id);

			if (!$series) {
				return Redirect::route('admin.modules.index')
					->with('error', 'Can\'t edit non-existing series.');
			}

			$series->fill($input);
			$series->save();

			return Redirect::route('admin.modules.index')
				->with('success', 'Series has been successfully updated.');
		}
	}

	public function store() {
		$input = Input::all();
		$validator = \Series::validate($input);

		if ($validator->fails()) {
			return Redirect::route('admin.modules.series.create', $input['module_id'])
				->withInput()
				->withErrors($validator);
		} else {
			Series::create($input);

			return Redirect::route('admin.modules.index')
				->with('success', 'Series has been successfully created.');
		}
	}

	public function destroy($id) {
		if (\Series::destroy($id) > 0) {
			return Redirect::route('admin.modules.index')
				->with('success', 'Series has been successfully deleted.');
		} else {
			return Redirect::route('admin.modules.index')
				->with('error', 'Cannot delete series because it doesn\'t exist or it is already deleted.');
		}
	}
}
