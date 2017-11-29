@extends('activities.base_layout')

<?php
$items_per_page = 2;
$class = 'ActivityTemplates\StoryCharacter';
?>

@section('wrapper_classes', 'pageWrap newInner clearfix qa_page')

@section('content')
<div class="contentSliderWrapper">
    <ul class="contentSlider">
        <li>
            <!-- <div class="convWrap askConv"> -->
            <div class="convWrap">
                <?php
                $i = 1;

                //$items = $data->items()->sorted()->get();
                $items = $data->items()->get();

                if(count($items)){
                    $index =($items[0]->is_right_side)?1:2;
                }

                foreach ($items as $item) {
                    $char = $item->character;
                    if ($i > 1 && ($i % $items_per_page) == 1) {
                        echo '</div></li><li><div class="convWrap">';
                    }
                    ?>

                    <div class="conv {{($item->is_right_side)?'right':''}}">
                        <div class="convImg">
                            <div class="convBg">
                                <img src="{{ asset('/uploads/'.$char->picture) }}" alt="" height="65">
                            </div>
                        </div>

                        <div class="convTextWrapper clearfix">
                            <div class="convText clearfix <?php
                            if (($i % $items_per_page) == 0) {
                                echo 'worried';
                            }
                            ?>">
                                <div class="convBg">
                                    <?php
                                    if (count($items) == 1) {
                                        $name = $items[0]->character->name;
                                    } else if ($item->is_right_side) {
                                        $name = $items[$i-$index]->character->name;
                                    } else {
                                        $name = $items[$i-2+$index]->character->name;
                                    }
                                    ?>

                                    Dear {{$name}},

                                    <br><br>
                                    {{ $item->text }}
                                    <br>
    <?php  if (($i % $items_per_page) == 1) { ?>
                                        <br><span class="rightalign">{{ $char->name }}</span>
    <?php } ?>
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
