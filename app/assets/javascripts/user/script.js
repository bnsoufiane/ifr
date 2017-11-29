activity_start_time = 0;
_min_freeform_chars = 50;
_min_blog_chars = 300;

if (!Object.create) {
    Object.create = function (o) {
        if (arguments.length > 1) {
            throw new Error('Object.create implementation only accepts the first parameter.');
        }
        function F() {
        }

        F.prototype = o;
        return new F();
    };
}

$(window).load(function () {

    //time_to_complete_activity();

    if ($('.bx-controls').length != 0 && $('.yesno_page').length == 0) {
        setTimeout(function () {
            $('.bx-pager .bx-pager-item .bx-pager-link').on('click', function (e) {
                setTimeout(function () {
                    if ($('.bx-pager .bx-pager-item .bx-pager-link:last').hasClass('active')) {

                        $('button.continueBtn').css('visibility', 'visible');
                        $('button.continueBtn span').show();

                    } else {
                        $('button.continueBtn').css('visibility', 'hidden');
                        $('button.continueBtn span').hide();
                    }
                }, 400);

            });
            if ($('.bx-pager .bx-pager-item .bx-pager-link').length > 1 && !$('.bx-pager .bx-pager-item .bx-pager-link:last').hasClass('active')) {
                $('button.continueBtn').css('visibility', 'hidden');
                $('button.continueBtn span').hide();
            } else {
                $('button.continueBtn').css('visibility', 'visible');
                $('button.continueBtn span').show();
            }
        }, 1000);
    }

    $('body').on('keyup', '#blog_textarea, .freeform_textarea, .fillblankPage input, .multiple_answersPage input, .calculationPage input', function () {
        var _flag_show_continue = true;
        $('.freeform_textarea').each(function () {
            if ($(this).val().length < _min_freeform_chars) {
                _flag_show_continue = false;
            }
        });

        if (($("#blog_textarea").length > 0) && $('#blog_textarea').val().length < _min_blog_chars) {
            _flag_show_continue = false;
        }

        $('.fillblankPage input, .multiple_answersPage input, .calculationPage input').each(function () {
            if ($(this).val().length < 1) {
                _flag_show_continue = false;
            }
        });

        if ($('.selectPage').length > 0 && $('.selectPage input:checked').length == 0) {
            _flag_show_continue = false;
        }

        if (($(".truefalsePage").length > 0) && ($('input[type="radio"]:checked').length < $(".truefalseRadios li").length )) {
            _flag_show_continue = false;
        }

        if (($(".yesno_page").length > 0) && ($('.yesno_page input[type="radio"]:not(:checked)').length > $('.yesno_page input[type="radio"]').length / 2 )) {
            _flag_show_continue = false;
        }

        if (_flag_show_continue) {
            $('button.continueBtn span').show();
        } else {
            $('button.continueBtn span').hide();
        }

    });


    $('body').on('change click', '.selectPage input, .truefalsePage input, .yesno_page input', function () {
        var _flag_show_continue = true;
        $('.freeform_textarea').each(function () {
            if ($(this).val().length < _min_freeform_chars) {
                _flag_show_continue = false;
            }
        });

        if (($("#blog_textarea").length > 0) && $('#blog_textarea').val().length < _min_blog_chars) {
            _flag_show_continue = false;
        }

        $('.fillblankPage input, .multiple_answersPage input, .calculationPage input').each(function () {
            if ($(this).val().length < 1) {
                _flag_show_continue = false;
            }
        });

        if ($('.selectPage').length > 0 && $('.selectPage input:checked').length == 0) {
            _flag_show_continue = false;
        }

        if (($(".truefalsePage").length > 0) && ($('input[type="radio"]:checked').length < $(".truefalseRadios li").length )) {
            _flag_show_continue = false;
        }

        if (($(".yesno_page").length > 0) && ($('.yesno_page input[type="radio"]:not(:checked)').length > $('.yesno_page input[type="radio"]').length / 2 )) {
            _flag_show_continue = false;
        }

        if (_flag_show_continue) {
            $('button.continueBtn span').show();
        } else {
            $('button.continueBtn span').hide();
        }
    });

    $('a img[alt="Print"]').on('click', function (e) {
        print_activity();
    });

    $('input[name="activity_id"]').first().attr("name", "parent_activity_id");

});


jQuery(document).ready(function ($) {

    $('body').on('click', '.bx-pager .bx-pager-item .bx-pager-link', function (e) {
        if ($(this).attr('data-slide-index') === $('.bx-pager .bx-pager-item .bx-pager-link:last').attr('data-slide-index')) {
            setTimeout(function () {
                $('.bx-pager .bx-pager-item .bx-pager-link').each(function () {
                    $(this).removeClass('active');
                });
                $('.bx-pager .bx-pager-item .bx-pager-link:last').addClass('active');
                $('button.continueBtn').css('visibility', 'visible');
                $('button.continueBtn span').show();
            }, 600);
        }

        setTimeout(function () {
            if ($('.bx-pager .bx-pager-item .bx-pager-link:last').hasClass('active')) {

                $('button.continueBtn').css('visibility', 'visible');
                $('button.continueBtn span').show();

            } else {
                $('button.continueBtn').css('visibility', 'hidden');
                $('button.continueBtn span').hide();
            }
        }, 400);

    });

    $('body').on('click', 'button.continueBtn', function (e) {
        if (($("#blog_textarea").length > 0) && $('#blog_textarea').val().length < _min_blog_chars) {
            return false;
        }

        $('.freeform_textarea').each(function () {
            if ($(this).val().length < _min_freeform_chars) {
                return false;
            }
        });

    });


    $(document).delegate('.freeform_textarea, #blog_textarea', 'keydown', function (e) {
        var keyCode = e.keyCode || e.which;

        if (keyCode == 9) {
            e.preventDefault();
            var start = $(this).get(0).selectionStart;
            var end = $(this).get(0).selectionEnd;

            $(this).val($(this).val().substring(0, start)
                + "\t"
                + $(this).val().substring(end));

            $(this).get(0).selectionStart =
                $(this).get(0).selectionEnd = start + 1;
        }
    });


    $('body').keypress(function (e) {
        var key = e.which;
        if (key == 13) {
            if (!($("textarea").is(":focus"))) {
                return false;
            }
        }
    });

    $('.title_text').each(function () {
        $(this).css('margin-left', $(this).siblings('.title_number').width());
    });


    if ($('.story_page').length != 0 || $('.qa_page').length != 0) {
        $('button.continueBtn span').hide();
    }

    if (($(".yesno_page").length > 0) && ($('.yesno_page input[type="radio"]:not(:checked)').length > $('.yesno_page input[type="radio"]').length / 2 )) {
        $('button.continueBtn span').hide();
    }

    $('.freeform_textarea').each(function () {
        if ($(this).val().length < _min_freeform_chars) {
            $('button.continueBtn span').hide();
        }
    });

    $('.fillblankPage input, .multiple_answersPage input, .calculationPage input').each(function () {
        if ($(this).val().length < 1) {
            $('button.continueBtn span').hide();
        }
    });

    if (!$("#blog_textarea").prop('disabled') && ($("#blog_textarea").length > 0) && $('#blog_textarea').val().length < _min_blog_chars) {
        $('button.continueBtn span').hide();
    }

    if ($('.selectPage').length > 0 && $('.selectPage input:checked').length == 0) {
        $('button.continueBtn span').hide();
    }

    if (($(".truefalsePage").length > 0) && ($('input[type="radio"]:checked').length < $(".truefalseRadios li").length )) {
        $('button.continueBtn span').hide();
    }

    $('.select_option_radio').on('click', function (e) {
        if ($(this).parent().hasClass('checked')) {
            $(this).parent().removeClass('checked');
            $(this).removeAttr('checked');
        }
    });


    $('body').enablePlaceholders();

    /* tracking event is not used yet in this version
     if ($('input[name="preview"]').length == 0) {
     events_tracking();
     }
     //*/

    $('.mainActions a img').tooltip({
        position: 'center right',
        offset: [0, 30]
    });

    $('.mainSteps a').tooltip({
        position: 'center right',
        offset: [0, 30]
    });

    $(".mainMenuWrapper").mouseenter(function () {
        $(this).closest('#header').find('.mainMenu').stop(false, true).slideDown('fast');
    });

    $(".mainMenuWrapper").mouseleave(function () {
        $(this).closest('#header').find('.mainMenu').stop(false, false).slideUp('fast');
    });

    $('.convSlider').bxSlider({
        auto: false,
        controls: false,
        pager: true,
        infiniteLoop: false
    });

    $('.contentSlider').bxSlider({
        nextSelector: '#nextQ',
        nextText: 'Next Question',
        auto: false,
        pager: true,
        infiniteLoop: false
    });

    $(function () {
        $(".customRadio").uniform();
    });

    $('.mainMenu .accordion a').on('click', function (e) {
        //alert($(this).attr("href"));
        //e.preventDefault();
        $(this).parent().siblings().children('.mainMenu .accordion a').removeClass('show').next().slideUp();
        $(this).toggleClass('show').next().slideToggle();
    });

    $(".various").fancybox({
        maxWidth: 500,
        maxHeight: 800,
        fitToView: true,
        width: '70%',
        height: '70%',
        autoSize: true,
        closeClick: true,
        openEffect: 'none',
        closeEffect: 'none',
        topRatio: 0,
        helpers: {
            overlay: null
        }
    });

    $('a[href="#non_answered_questions"]').fancybox({
        maxWidth: 500,
        maxHeight: 800,
        fitToView: true,
        width: '70%',
        height: '70%',
        autoSize: true,
        closeClick: true,
        openEffect: 'none',
        closeEffect: 'none'
    });

    complete_answers_validation();
});

(function ($, document) {

    var enablePlaceholders = {
        init: function (el) {
            var $t = this;
            $t.el = $(el).data('hasplaceholder', true);
            $t.placeholder = $t.el.attr('placeholder');
            $t.addPlaceholder($t.el);
        } // init

        , clearValue: function (e) {
            if ($(e).val() === $(e).data('placeholder')) {
                $(e).val('');
            }
        }//clearValue

        , addPlaceholder: function () {
            var $t = this;
            $t.maybeShowPlaceholder();
            $t.el.bind('blur.ntz_placeholder', $.proxy($t.maybeShowPlaceholder, $t));
            $t.el.bind('focus.ntz_placeholder', $.proxy($t.maybeHidePlaceholder, $t));
        } //addPlaceholder

        , maybeShowPlaceholder: function () {
            var $t = this;
            if ($.trim($t.el.val()) !== '') {
                return;
            }
            $t.el
                .addClass('placeholder')
                .val($t.placeholder);
        }//maybeShowPlaceholder

        , maybeHidePlaceholder: function () {
            var $t = this;
            if ($t.el.hasClass('placeholder') &&
                $t.el.val() == $t.placeholder) {
                $t.el.val('')
            }
        }//maybeHidePlaceholder
    };

    $.fn.enablePlaceholders = function () {
        var fakeInput = document.createElement("input"),
            nativePlaceholder = ("placeholder" in fakeInput);

        if (!nativePlaceholder) {

            $('input[placeholder], textarea[placeholder]').filter(function () {
                return !$(this).data('hasplaceholder')
            }).each(function () {
                var obj = Object.create(enablePlaceholders);
                obj.init(this);
            });

            return this;
        }
    };

})(jQuery, document);


// http://paulirish.com/2011/requestanimationframe-for-smart-animating/
// http://my.opera.com/emoller/blog/2011/12/20/requestanimationframe-for-smart-er-animating

// requestAnimationFrame polyfill by Erik Möller
// fixes from Paul Irish and Tino Zijdel

(function () {
    var lastTime = 0,
        vendors = ['ms', 'moz', 'webkit', 'o'];

    for (var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
        window.requestAnimationFrame = window[vendors[x] + 'RequestAnimationFrame'];
        window.cancelAnimationFrame = window[vendors[x] + 'CancelAnimationFrame'] || window[vendors[x] + 'CancelRequestAnimationFrame'];
    }

    if (!window.requestAnimationFrame) {
        window.requestAnimationFrame = function (callback, element) {
            var currTime = new Date().getTime();
            var timeToCall = Math.max(0, 16 - (currTime - lastTime));
            var id = window.setTimeout(function () {
                    callback(currTime + timeToCall);
                },
                timeToCall);
            lastTime = currTime + timeToCall;
            return id;
        };
    }

    if (!window.cancelAnimationFrame) {
        window.cancelAnimationFrame = function (id) {
            clearTimeout(id);
        };
    }
}());

function complete_answers_validation() {

    var _allFreeFormsFilled = true;
    var _allBlogsFilled = true;
    $('.freeform_textarea').each(function () {
        if ($(this).val().length < _min_freeform_chars) {
            _allFreeFormsFilled = false;
        }
    });

    if (($("#blog_textarea").length > 0) && $('#blog_textarea').val().length < _min_blog_chars) {
        _allBlogsFilled = false;
    }

    if (!_allFreeFormsFilled || !_allBlogsFilled
        || (($(".blogPage").length > 0) && ($('textarea').val().length < _min_blog_chars))
        || (($(".selectPage").length > 0) && ($('input[type="radio"]:checked').length < 1))
        || (($(".selectPage").length > 0) && ($('input[type="radio"]:checked').length < 1))
        || (($(".yesno_page").length > 0) && ($('input[type="radio"]:not(:checked)').length > $('input[type="radio"]').length / 2 ))
        || (($('.calculationPage').length > 0) && ($('.lossField input:text[value=""]').length > 0) )
        || ((($('.multiple_answersPage').length > 0) || ($('.fillblankPage').length > 0) ) && ($('input:text[value=""]').length > 0) )
        || (($(".truefalsePage").length > 0) && ($('input[type="radio"]:checked').length < $(".truefalseRadios li").length ))
    ) {
        $('img[alt="Feedback"]').parent().attr("href", "#feedback_msg");
    } else {
        $('img[alt="Feedback"]').parent().attr("href", "#feedback");
    }

    $('body').on('change click', '.selectPage input, .truefalsePage input, .yesno_page input', function () {

        var _allFreeFormsFilled = true;
        var _allBlogsFilled = true;
        $('.freeform_textarea').each(function () {
            if ($(this).val().length < _min_freeform_chars) {
                _allFreeFormsFilled = false;
            }
        });

        if (($("#blog_textarea").length > 0) && $('#blog_textarea').val().length < _min_blog_chars) {
            _allBlogsFilled = false;
        }

        if (!_allFreeFormsFilled || !_allBlogsFilled
            || (($(".blogPage").length > 0) && ($('textarea').val().length < _min_blog_chars))
            || (($(".selectPage").length > 0) && ($('input[type="radio"]:checked').length < 1))
            || (($(".selectPage").length > 0) && ($('input[type="radio"]:checked').length < 1))
            || (($(".yesno_page").length > 0) && ($('input[type="radio"]:not(:checked)').length > $('input[type="radio"]').length / 2 ))
            || (($('.calculationPage').length > 0) && ($('.lossField input:text[value=""]').length > 0) )
            || ((($('.multiple_answersPage').length > 0) || ($('.fillblankPage').length > 0) ) && ($('input:text[value=""]').length > 0) )
            || (($(".truefalsePage").length > 0) && ($('input[type="radio"]:checked').length < $(".truefalseRadios li").length ))
        ) {
            $('img[alt="Feedback"]').parent().attr("href", "#feedback_msg");
        } else {
            $('img[alt="Feedback"]').parent().attr("href", "#feedback");
        }

    });

    $('body').on('keyup', '#blog_textarea, .freeform_textarea, .fillblankPage input, .multiple_answersPage input, .calculationPage input', function () {

        var _allFreeFormsFilled = true;
        var _allBlogsFilled = true;
        $('.freeform_textarea').each(function () {
            if ($(this).val().length < _min_freeform_chars) {
                _allFreeFormsFilled = false;
            }
        });

        if (($("#blog_textarea").length > 0) && $('#blog_textarea').val().length < _min_blog_chars) {
            _allBlogsFilled = false;
        }

        if (!_allFreeFormsFilled || !_allBlogsFilled
            || (($(".blogPage").length > 0) && ($('textarea').val().length < _min_blog_chars))
            || (($(".selectPage").length > 0) && ($('input[type="radio"]:checked').length < 1))
            || (($(".selectPage").length > 0) && ($('input[type="radio"]:checked').length < 1))
            || (($(".yesno_page").length > 0) && ($('input[type="radio"]:not(:checked)').length > $('input[type="radio"]').length / 2 ))
            || (($('.calculationPage').length > 0) && ($('.lossField input:text[value=""]').length > 0) )
            || ((($('.multiple_answersPage').length > 0) || ($('.fillblankPage').length > 0) ) && ($('input:text[value=""]').length > 0) )
            || (($(".truefalsePage").length > 0) && ($('input[type="radio"]:checked').length < $(".truefalseRadios li").length ))
        ) {
            $('img[alt="Feedback"]').parent().attr("href", "#feedback_msg");
        } else {
            $('img[alt="Feedback"]').parent().attr("href", "#feedback");
        }

    });

    if ($('.assessmentPage:not(.truefalsePage)').length > 0) {
        $('img[alt="Feedback"]').parent().attr("href", "#feedback");
    }

}
/* tracking events in not used in this version
 function events_tracking() {

 $(window).load(function ($) {
 track_event("VIEWED_LESSON");
 });

 $('a img[alt="Feedback"]').on('click', function (e) {
 track_event("REQUESTED_FEEDBACK");
 });
 $('a img[alt="Sound"]').on('click', function (e) {
 track_event("$LISTENED_AUDIO");
 });
 $('a img[alt="Print"]').on('click', function (e) {
 //track_event("$PRINTED_LESSON");
 print_activity();
 });

 }

 function track_event(event_type) {
 var _activity_id = $('input#activity_id').val();
 var _user_id = $('input#user_id').val();

 var params = {
 'activity_id': _activity_id,
 'user_id': _user_id,
 'event_type': event_type
 };
 $.ajax({
 type: "GET",
 //url: window.location.protocol + "//" +window.location.host + "/events/store",
 url: "/events/store",
 data: params,
 success: function (response) {
 if (response) {

 }
 },
 error: function (jqXHR, textStatus, errorThrown) {
 console.log(jqXHR);
 }
 });

 }
 //*/

function print_activity() {

    var data = {
        "student_id": $('#user_id').val(),
        "activity_id": $('#activity_id').val()
    };

    if (($("#blog_textarea").length < 1) && ($(".freeform_textarea").length < 1) && ($(".fillblankPage").length < 1) &&
        ($(".multiple_answersPage").length < 1) && ($(".calculationPage").length < 1) && ($(".selectPage").length < 1) &&
        ($(".truefalsePage").length < 1) && ($(".yesno_page").length < 1) && ($(".assessmentPage").length < 1)) {
        alert('This activity can\'t be printed');
    } else {
        var _newTabUrl = '/print_activity?' + jQuery.param(data);
        var win = window.open(_newTabUrl, '_blank');
        win.focus();
    }
}


// this function is not used for now : we are not tracking the time needed to complete an activity yet
/*
 function time_to_complete_activity() {

 $(window).load(function ($) {
 activity_start_time = new Date().getTime();
 });

 $('button.continueBtn, button.submit').on('click', function (e) {
 if (($(".assessmentPage").length > 0) && ($('input[type="radio"]:checked').length < $(".assessmentRadios li").length )) {

 var _non_answered_questions = '';

 $('.assessmentRadios li').each(function (i) {
 var _question_number = $(this).find('.title_number').text().replace(".", "");
 var _question_answered = $(this).find('input[type="radio"]:checked').length;
 _question_answered = (_question_answered > 0) ? true : false;

 if (!_question_answered) {
 _non_answered_questions += _question_number + '<br/>';
 }
 });

 $('#non_answered_questions').find('p').html(_non_answered_questions);

 $('a[href="#non_answered_questions"]').trigger("click");

 return false;
 }

 $('input[name="activity_id"]').first().attr("name", "parent_activity_id");
 var activity_end_time = new Date().getTime();
 $('input[name="time_to_complete_activity"]').val(activity_end_time - activity_start_time);
 });

 }
 //*/