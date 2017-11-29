<?php
namespace Admin;

use \BaseController as BaseAppController;

class BaseController extends BaseAppController {
	protected $layout = 'admin.layout.base';

	public static function handleUpload($paramName) {
        $file = \Request::instance()->getContent();

        $path = pathinfo($_GET[$paramName]);
        $filename = substr(sha1(time()), 0, 10) . '.' . addslashes($path['extension']);

        // FIXME: move uploads folder name to constants
		$filepath = public_path() . '/uploads/' . $filename;
        file_put_contents($filepath, $file);

		return array(
			'filepath' => $filepath,
			'filename' => $filename
		);
	}
}
