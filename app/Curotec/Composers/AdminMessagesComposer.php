<?php


namespace Curotec\Composers;


class AdminMessagesComposer {
    public function compose($view) {
        $messages = new \stdClass();

        $messages->success = \Session::get('success');
        $messages->error = \Session::get('error');

        $view->with('messages', $messages);
    }
}
