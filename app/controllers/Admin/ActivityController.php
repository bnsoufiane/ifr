<?php

class ActivityController extends BaseController {

    /**
     * Renders an activity template by a provided type.
     * @param string $type
     */
    public function render($activityId) {
        
        $activity = new Activity();

        $activity = $activity::where('id', '=', $activityId)->get();

        $template_type = $activity[0]['template_type'];
        $template_id = $activity[0]['template_id'];

        $meta = $template_type::getMetaData();

        switch ($template_type) {
            case 'ActivityTemplates\Select':
                $data["options"] = $template_type::find($template_id)->options()->get();
                return \View::make($meta['user_template'], $data);
                break;

            default:
                throw new \Exception('Wrong activity template.');
        }
    }

}
