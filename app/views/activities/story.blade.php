@extends('activities.base_layout')

<?php
$items_per_page = 4;
$class = 'ActivityTemplates\StoryCharacter';
?>

@section('wrapper_classes', 'pageWrap newInner clearfix story_page')

@section('content')
<div class="convSliderWrapper">
    <ul class="convSlider">
        <li>
            <div class="convWrap">
                <?php
                $i = 1;

                foreach ($data->items()->sorted()->get() as $item) {
                    $char = $item->character;
                    if ($i > 1 && ($i % $items_per_page) == 1) {
                        echo '</div></li><li><div class="convWrap">';
                    }
                    ?>

                    <div class="conv {{($item->is_right_side)?'right':''}}">
                        <div class="convImgWrapper">
                            <div class="convImg">
                                <div class="convBg">
                                    <img src="{{ asset('/uploads/'.$char->picture) }}" alt="">
                                </div>
                            </div>
                            <div class="convUsername">{{ $char->name }}</div>
                        </div>

                        <div class="convTextWrapper clearfix">
                            <div class="convText clearfix">
                                <div class="convBg">
                                    {{ $item->text }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    $i++;
                }
                ?>
            </div>
        </li>
    </ul>
</div>
@stop
