var _show_correct = "<p class='true'>Correct</p>";
var _show_incorrect = "<p class='false'>Incorrect</p>";

jQuery(document).ready(function ($) {

    click_once_checkbox();
});

function click_once_checkbox() {
    $(".radioQuestions input").change(function () {
        if (this.checked) {
            $(this).parents('.radioWrap').find('.radio span').removeClass('checked');
            $(this).parent().addClass('checked');
            $(this).parents('.radioWrap').find('input').attr('disabled', true);
            var _slide_index = $(this).parents('li').index() + 1;
            var _question_index = $(this).parents('.radioWrap').index() + 1;
            var _checkbox_index = ($(this).parents('.option1').length > 0) ? 1 : 2;
            show_answers($(this), _slide_index, _question_index, _checkbox_index);
        }
    });

    // Post data from radio buttons.
    $('form').on('submit', function () {
        $(this).find('input').removeAttr('disabled');
    });
}

function show_answers(input, _slide_index, _question_index, _checkbox_index) {
    var answer = parseInt(input.val()) + 1;
    var correct_answer = parseInt(input.parents('.radioOptions').attr('graded'));

    // not an opinion : marked as yes or no
    if (correct_answer) {
        var result = (answer == correct_answer);

        var _questions_per_slide = $('ul.contentSlider li:first-child .radioQuestions .radioWrap').length;
        var _question = (_slide_index - 1) * _questions_per_slide + _question_index;
        $(".bx-viewport").css("height", "auto");
        $("<br/><div class='radioAnswer'>" + (result ? _show_correct : _show_incorrect) + "</div>").insertAfter($("ul.contentSlider li:eq(" + (_slide_index - 1) + ") .radioQuestions .radioWrap:eq(" + (_question_index - 1) + ") .radioLabel"));
    }

}
