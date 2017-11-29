var _show_correct = "<p class='true'>Correct</p>";

jQuery(document).ready(function($) {

    if ($('.assessmentPage').length > 0) {
        select_assessement_answer();
    }
});

function select_assessement_answer() {
    $(".assessmentRadios input").change(function() {
        if (this.checked) {
            $(this).parents('li').find('input').attr('disabled', true);
            $(this).parent().addClass('checked');

            if ($(this).attr('graded') == '2') {
                $("<br/><div class='radioAnswer'>" + _show_correct + "</div>").
                        appendTo($(this).parents('li'));
            } else {
                $("<br/><div class='radioAnswer'><p class='false'>" + $(this).parents('li').find('.section_title').attr('wrong_answer_desc') + "</p></div>").
                        appendTo($(this).parents('li'));
            }
        }
    });

    // Post data from radio buttons.
    $('form').on('submit', function() {
        $(this).find('input').removeAttr('disabled');
    });
}
