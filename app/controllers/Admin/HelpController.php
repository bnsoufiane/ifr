<?php

namespace Admin;

use View;

class HelpController extends BaseController {
    public function showIndex() {
        $this->layout->content = View::make('admin/help/index');
    }
}
