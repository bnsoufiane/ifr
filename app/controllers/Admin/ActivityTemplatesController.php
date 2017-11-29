<?php


namespace Admin;


class ActivityTemplatesController extends BaseController {
    /**
     * Renders an activity template by a provided type.
     * @param string $type
     */
    public function render($type) {
        $type = 'ActivityTemplates\\' . $type;

        try {
            $activityTemplate = new $type();

            if (!($activityTemplate instanceof \ActivityTemplate)) {
                throw new \Exception('Wrong activity template.');
            }
            
        } catch (\Exception $e) {
            return \Response::make($e->getMessage(), 400);
        }

        $meta = $activityTemplate->getMetaData();

        return \View::make($meta['admin_template']);
    }
}
