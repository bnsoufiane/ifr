jQuery(document).ready(function ($) {
    $('.mainContentWrapper').on('click', '.multiple_answers_example_text', function () {
        $(this).next().find('input').focus();
    });

});
