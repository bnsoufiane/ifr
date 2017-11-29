jQuery(document).ready(function($) {

	$('body').on('click', 'a.lesson_optional', function() {
        $(this).toggleClass("lesson_optional_on");
        $(this).toggleClass("lesson_optional_off");
        
        var data = {
            "optional": ($(this).hasClass('lesson_optional_on') ? 0 : 1)
        }

        $.ajax({
            url: 'add_optional_lesson/' + $(this).attr("lesson_id"),
            type: 'POST',
            dataType: 'json',
            data: data
        }).success(function(result) {
        }).error(function(err) {
        });
    });


    $('body').on('click', '.change_password .continueBtn', function() {

        if($("#new_password").val().length <1){
            alert('The new password field is required.');
            return false;
        }

        if($("#new_password_confirmation").val().length <1){
            alert('The new password confirmation field is required.');
            return false;
        }

        if($("#new_password").val().length <5){
            alert('The new password must be at least 5 characters.');
            return false;
        }

        if($("#new_password_confirmation").val().length <5){
            alert('The new password confirmation must be at least 5 characters.');
            return false;
        }

        if($("#new_password").val() != $("#new_password_confirmation").val()){
            alert('The new password confirmation does not match.');
            return false;
        }

        if($("#new_password").val() == $("#default_password").val()){
            alert("You can't use the same default password. Please enter a different password.");
            return false;
        }

    });

});

