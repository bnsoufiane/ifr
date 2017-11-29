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

jQuery(document).ready(function ($) {

    $('body').keypress(function (e) {
        var key = e.which;
        if(key == 13)
        {
            if(!($("textarea").is(":focus"))){
                return false;
            }
        }
    });

    $('.title_text').each(function () {
        $(this).css('margin-left',$(this).siblings('.title_number').width());
    });

    $('body').enablePlaceholders();

    $('.mainActions a img').tooltip({
        position: 'center right',
        offset: [0, 30]
    });

    $('.mainSteps a').tooltip({
        position: 'center right',
        offset: [0, 30]
    });

    $( ".mainMenuWrapper" ).mouseenter(function() {
        $(this).closest('#header').find('.mainMenu').stop(true, false).slideDown('fast');
    });

    $( ".mainMenuWrapper" ).mouseleave(function() {
        $(this).closest('#header').find('.mainMenu').stop(true, false).slideUp('fast');
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
        topRatio:0,
        helpers: {
            overlay: null
        }
    });

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

