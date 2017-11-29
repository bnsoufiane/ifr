<?php


namespace Admin;

use View;
use Redirect;
use Input;

use Module;

class ModulesController extends BaseController
{
    public function index()
    {
        $modulesList = \Module::with('series', 'series.lessons')->get();

        $view = View::make('admin/modules/index')
            ->with('modules', $modulesList);

        $this->layout->content = $view;
    }

    public function create()
    {
        $view = View::make('admin/modules/create')
            ->with('module', new \Module())
            ->with('route', array('admin.modules.store'))
            ->with('skins', $this->getModuleSkins());

        $this->layout->content = $view;
    }

    public function edit($id)
    {
        $module = \Module::find($id);

        if (!$module) {
            return Redirect::route('admin.modules.index')
                ->with('error', 'Product not found.');
        }

        $view = View::make('admin/modules/edit')
            ->with('module', $module)
            ->with('route', array('admin.modules.update', $module->id))
            ->with('method', 'PUT')
            ->with('skins', $this->getModuleSkins());

        $this->layout->content = $view;
    }

    public function update($id)
    {
        if (Input::has('cancel')) {
            return Redirect::route('admin.modules.index');
        }

        $input = Input::all();
        $validator = \Module::validate($input, $id);

        if ($validator->fails()) {
            return Redirect::route('admin.modules.edit', $id)
                ->withErrors($validator);
        } else {
            // Updating the Product
            $module = Module::find($id);

            if (!$module) {
                return Redirect::route('admin.modules.index')
                    ->with('error', 'Can\'t edit a non-existing product.');
            }

            $module->fill($input);
            $module->save();

            return Redirect::route('admin.modules.index')
                ->with('success', 'Series has been successfully updated.');
        }
    }

    public function store()
    {
        if (Input::has('cancel')) {
            return Redirect::route('admin.modules.index');
        }

        $input = Input::all();
        $validator = Module::validate($input);

        if ($validator->fails()) {
            return Redirect::route('admin.modules.create')
                ->withInput()
                ->withErrors($validator);
        } else {
            // Creating a new product
            Module::create($input);

            return Redirect::route('admin.modules.index')
                ->with('success', 'A product has been successfully created.');
        }
    }

    public function destroy($id)
    {
        if (\Module::destroy($id) > 0) {
            return Redirect::route('admin.modules.index')
                ->with('success', 'Product has been successfully deleted.');
        } else {
            return Redirect::route('admin.modules.index')
                ->with('error', 'Cannot delete a product because it doesn\'t exist or it is already deleted.');
        }
    }

    /**
     * @todo Move to a service class + better implementation
     * @private
     */
    private function getModuleSkins()
    {
        $skins = [];

        foreach (get_declared_classes() as $class) {
            if (in_array('Curotec\\ModuleSkin', array_values(class_parents($class)))) {
                $name = call_user_func_array(array($class, 'getName'), array());

                $skins[$class] = $name;
            }
        }

        return $skins;
    }

    /**
     * Returns a list of available products in JSON format.
     */
    public function available()
    {
        $series = \Series::all();

        foreach ($series as $key => $serie) {
            if ($serie->module === null) {
                unset($series[$key]);
            } else {
                $serie->title = $serie->module->title . " - " . $serie->title;
            }
        }

        $series = $series->toArray();

        return array_values($series);
    }

}
