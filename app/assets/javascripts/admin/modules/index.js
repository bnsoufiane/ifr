/* List of products and series */

$(function() {
    $('body').on('click', 'a.lesson_optional', function() {
        $(this).toggleClass("lesson_optional_on");
        $(this).toggleClass("lesson_optional_off");
        
        var data = {
            "optional": ($(this).hasClass('lesson_optional_on') ? 0 : 1)
        }

        $.ajax({
            url: 'lessons/' + $(this).attr("lesson_id") + '/make_optional',
            type: 'POST',
            dataType: 'json',
            data: data
        }).success(function(result) {
        }).error(function(err) {
        });
    });

    // Add remove function to table items.
    $('.modules-table').on('click', '[data-action="remove"]', function() {
        if (!confirm('Are you sure you want to delete this item?')) {
            return false;
        }

        var row = $(this).parents('tr');

        $.post($(this).attr('href'), {'_method': 'DELETE'})
                .done(function() {
                    // Remove all nested items.
                    var level = row.attr('class');
                    var next = row.next('tr');

                    row.remove();

                    while (next.length > 0 && next.attr('class') != level) {
                        var tmp = next.next('tr');
                        next.remove();

                        next = tmp;
                    }
                });

        return false;
    });
});
