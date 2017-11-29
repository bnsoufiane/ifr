@extends('activities.base_layout')

@section('wrapper_classes', 'pageWrap newInner lightInner clearfix fillblankPage')

@section('content_unwrapped')
<div class="solid_border_fix">
    <div class="mainContentWrapper">
        <div class="mainContent">
            <p>
                {{{nl2br($data->description)}}}
            </p>
        </div>
    </div>

    <div class="mainContentWrapper secondWrapper">
        <div class="mainContent">
            <table class="lossTable fillnlank">

                <tbody>

                    <?php $i=0; ?>
                    @foreach($data->items as $item)
                        <tr>
                            <td style="width: 25%">
                                <div class="lossField">
                                    <input name="fillblank_{{$current_activity->id}}[]" maxlength="20" type="text" value="{{ ($hasAnswer ? $answerData->getAnswer($i) : '') }}" @if($hasAnswer || (isset($assessment_done) && $assessment_done)) disabled @endif>
                                </div>
                            </td>
                            <?php
                                try {
                                    list($number, $text) = explode(".", $item->name, 2);
                                if(strlen($number)>3){
                                    $number='';
                                        $text = $item->name;
                                }else{
                                    $number .=".&nbsp;";
                                }
                                } catch (\Exception $e) {
                                    $text = $item->name;
                                }
                            ?>
                            <?php $i++; ?>
                            <td style="width: 75%" class="fillblank_text"><div class="title_number">{{"$i.&nbsp;"}}</div><div class="title_text">{{$text}}</div></td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

        </div>
    </div>
</div>
@stop

