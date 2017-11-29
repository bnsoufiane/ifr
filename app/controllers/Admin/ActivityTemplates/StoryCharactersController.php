<?php

namespace Admin\ActivityTemplates;

use Input;
use Admin\BaseController;
use ActivityTemplates\StoryCharacter;

class StoryCharactersController extends BaseController {
	public function index() {
		return StoryCharacter::all();
	}

	public function store() {
		return StoryCharacter::create(Input::all());
	}

	public function update($id) {
		return (StoryCharacter::find($id)->update(Input::all()))?1:0;
	}

	public function destroy($id) {
		return array('result' => StoryCharacter::destroy($id) > 0);
	}

	public function uploadPicture() {
        if (!\Input::get('upload')) {
            return \Response::make('No file was provided.', 400);
        }

		$file = BaseController::handleUpload('upload');

        return array(
			'url' => '/uploads/' . $file['filename'],
			'filename' => $file['filename']
		);
	}
}
