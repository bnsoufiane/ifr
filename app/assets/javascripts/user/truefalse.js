var _show_correct = "<p class='true'>Correct</p>";

jQuery(document).ready(function($) {

    if ($('.assessmentPage').length > 0) {
        select_assessement_answer();
    }
});

function select_assessement_answer() {
    $(".truefalse input").change(function() {
        if (this.checked) {
            $(this).parents('li').find('input').attr('disabled', true);
            $(this).parent().addClass('checked');
        }
    });

    // Post data from radio buttons.
    $('form').on('submit', function() {
        $(this).find('input').removeAttr('disabled');
    });
}
