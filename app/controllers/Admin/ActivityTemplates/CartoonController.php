<?php


namespace Admin\ActivityTemplates;

use \Admin\BaseController;


class CartoonController extends BaseController {
	public function uploadPicture() {
        if (!\Input::get('picture')) {
            return \Response::make('No file was provided.', 400);
        }

		$file = self::handleUpload('picture');

        return array('file' => $file['filename']);
	}
}
