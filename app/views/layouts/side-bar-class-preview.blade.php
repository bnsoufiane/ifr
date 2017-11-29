<div class="pageNav">
    <a href="/sign-out" class="sign_out">Sign Out</a>

    <ul class="mainSteps">
        <?php $i = 1; ?>

        @foreach ($activities as $item)
            <?php
             $isAssessment = $item->template_type == "ActivityTemplates\\Assessment";
             $nowAssessmentActivity = ($current_activity->template_type=="ActivityTemplates\\Assessment");

             $class = ( ($nowAssessmentActivity && ($item->id != $activityId) )||($item->order < $current_activity->order) || ($item->order ==0 && $current_activity->order== 0 && ($item->id < $activityId)) )? "checked" : (($item->id == $activityId) ? "current" : "");
             if($isAssessment && !$nowAssessmentActivity){
                $class="";
             }

            ?>

            <li class="{{$class}}">
                @if($is_school_preview)
                    <a href="{{URL::route('admin.schools.preview_activity', array($item->id))}}" title="<span> {{htmlspecialchars($item->title)}}</span>">{{$i}}</a>
                @else
                    <a href="{{URL::route('admin.classes.preview_activity', array($class_id, $item->id))}}" title="<span> {{htmlspecialchars($item->title)}}</span>">{{$i}}</a>
                @endif
            </li>
            <?php $i++; ?>
        @endforeach

    </ul>

    <ul class="mainActions">
        <li>
            <a  class="various" href="#feedback" href="javascript:;" class="disabled"><img src="<?php echo asset('/assets/f-icon.png'); ?>" alt="Feedback" title="<span>Feedback</span>"></a>
        </li>
        <!--<li>
            <a href="javascript:;"><img src="<?php echo asset('/assets/sound-icon.png'); ?>" alt="Sound" title="<span>Sound</span>"></a>
        </li>-->
        <li>
            <a href="javascript:;"><img src="<?php echo asset('/assets/print-icon.png'); ?>" title="<span>Print</span>"></a>
        </li>
    </ul>
</div>