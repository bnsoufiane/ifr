<div class="pageNav">
    <a href="/sign-out" class="sign_out">Sign Out</a>

    <ul class="mainSteps">
        <?php

        $user_id = \Sentry::getUser()->id;

        $i = 1;

        foreach ($activities as $item) {
            $isAssessment = $item->template_type == "ActivityTemplates\\Assessment";

            $nowAssessmentActivity = ($current_activity->template_type=="ActivityTemplates\\Assessment");


            $student_item_answers = \StudentAnswer::whereRaw('student_id = ? and activity_id = ?', array($user_id, $item->id))->first();

            if($item->id == $activityId){
                $class = "current";
            }else if(($student_item_answers!=null)){
                $class = "checked";
            }else{
                $class = "";
            }

            //$class = ( ($nowAssessmentActivity && ($item->id != $activityId) )||($item->order < $current_activity->order) || ($item->order ==0 && $current_activity->order== 0 && ($item->id < $activityId)) )? "checked" : (($item->id == $activityId) ? "current" : "");

            if(!$isAssessment || ($isAssessment && ($item->id == $activityId)) || $assessment_done){
                echo '<li class="' . $class . '"><a href="/activities/'.$item->id.'" title="<span>' . htmlspecialchars($item->title) . '</span>">' . $i . '</a></li>';
            }
            $i++;
        }

        ?>
    </ul>

    <ul class="mainActions">
        <li>
            <a  class="various" href="#feedback_msg" href="javascript:;" class="disabled"><img src="<?php echo asset('/assets/f-icon.png'); ?>" alt="Feedback" title="<span>Feedback</span>"></a>
        </li>
        <!--<li>
            <a href="javascript:;"><img src="<?php echo asset('/assets/sound-icon.png'); ?>" alt="Sound" title="<span>Sound</span>"></a>
        </li>-->
        <li>
            <a href="javascript:;"><img src="<?php echo asset('/assets/print-icon.png'); ?>" alt="Print" title="<span>Print</span>"></a>
        </li>
    </ul>
</div>