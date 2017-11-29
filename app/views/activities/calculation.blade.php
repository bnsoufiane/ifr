@extends('activities.base_layout')

@section('wrapper_classes', 'pageWrap newInner lightInner clearfix calculationPage')

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
            <table class="lossTable">
                <thead>
                    <tr>
                        <th><span>{{$data->column_1}}</span></th>
                        <th><span>{{$data->column_2}}</span></th>
                        <th><span>{{$data->column_3}}</span></th>
                    </tr>
                </thead>

                <tbody>

                    <?php $i=0; ?>
                    @foreach ($data->items as $item)
                        <tr><td>{{$item->name}}</td><td>${{$item->employer_cost}} {{$item->cost_unit}}</td>
                        <td><div class="lossCurrency">$</div><div class="lossField">
                            <input type="text" name="calculation_{{$current_activity->id}}[]" value="{{ ($hasAnswer && $answerData ? $answerData->getAnswer($i) : '') }}" @if($hasAnswer || (isset($assessment_done) && $assessment_done)) disabled @endif>
                        </div></td></tr>
                        <?php $i++; ?>
                    @endforeach

                </tbody>
            </table>

            @foreach ($data->footers as $footer)
                <div class="borderedTable">
                    <table class="lossTable">
                        <tr>
                            <td class="rowTxt">{{$footer->name}}</td>
                            <td class="rowValue"><div class="lossCurrency">$</div><div class="lossField">
                                <input type="text" name="calculation_footer_{{$current_activity->id}}[]" value="{{ ($hasAnswer && $answerData ? $answerData->getAnswer($i) : '') }}" @if($hasAnswer || (isset($assessment_done)  && $assessment_done)) disabled @endif>
                            </div></td>
                        </tr>
                    </table>
                </div>
                <?php $i++; ?>
            @endforeach

        </div>
    </div>
</div>
@stop

