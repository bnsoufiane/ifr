var App = function () {

  var config = {//Basic Config
    tooltip: true,
    popover: true,
    nanoScroller: true,
    nestableLists: true,
    hiddenElements: true,
    bootstrapSwitch:true,
    dateTime:true,
    select2:true,
    tags:true,
    slider:true
  }; 
   
  /*Nestable Lists*/
  var nestable = function(){
    //Watch for list changes and show serialized output
    function update_out(selector, sel2){
      var out = $(selector).nestable('serialize');
      $(sel2).html(window.JSON.stringify(out));
    }
    
    update_out('#list1',"#out1");
    update_out('#list2',"#out2");
    
    $('#list1').on('change', function() {
      update_out('#list1',"#out1");
    });
    
    $('#list2').on('change', function() {
      update_out('#list2',"#out2");
    });
  };//End of Nestable Lists
  
  
      function toggleSideBar(_this){
        var b = $("#sidebar-collapse")[0];
        var w = $("#cl-wrapper");
        var s = $(".cl-sidebar");
        
        if(w.hasClass("sb-collapsed")){
          $(".fa",b).addClass("fa-angle-left").removeClass("fa-angle-right");
          w.removeClass("sb-collapsed");
        }else{
          $(".fa",b).removeClass("fa-angle-left").addClass("fa-angle-right");
          w.addClass("sb-collapsed");
        }
        updateHeight();
      }
      
      function updateHeight(){
        if(!$("#cl-wrapper").hasClass("fixed-menu")){
          var button = $("#cl-wrapper .collapse-button").outerHeight();
          var navH = $("#head-nav").height();
          //var document = $(document).height();
          var cont = $("#pcont").height();
          var sidebar = ($(window).width() > 755 && $(window).width() < 963)?0:$("#cl-wrapper .menu-space .content").height();
          var windowH = $(window).height();
          
          if(sidebar < windowH && cont < windowH){
            if(($(window).width() > 755 && $(window).width() < 963)){
              var height = windowH;
            }else{
              var height = windowH - button - navH;
            }
          }else if((sidebar < cont && sidebar > windowH) || (sidebar < windowH && sidebar < cont)){
            var height = cont + button + navH;
          }else if(sidebar > windowH && sidebar > cont){
            var height = sidebar + button;
          }  
          
          // var height = ($("#pcont").height() < $(window).height())?$(window).height():$(document).height();
          $("#cl-wrapper .menu-space").css("min-height",height);
        }else{
          $("#cl-wrapper .nscroller").nanoScroller({ preventPageScrolling: true });
        }
      }
        
  return {
   
    init: function (options) {
      //Extends basic config with options
      $.extend( config, options );
      
      /*VERTICAL MENU*/
      $(".cl-vnavigation li ul").each(function(){
        $(this).parent().addClass("parent");
      });
      
      $(".cl-vnavigation li ul li.active").each(function(){
        $(this).parent().show().parent().addClass("open");
        //setTimeout(function(){updateHeight();},200);
      });
      
      $(".cl-vnavigation").delegate(".parent > a","click",function(e){
        $(".cl-vnavigation .parent.open > ul").not($(this).parent().find("ul")).slideUp(300, 'swing',function(){
           $(this).parent().removeClass("open");
        });
        
        var ul = $(this).parent().find("ul");
        ul.slideToggle(300, 'swing', function () {
          var p = $(this).parent();
          if(p.hasClass("open")){
            p.removeClass("open");
          }else{
            p.addClass("open");
          }
          //var menuH = $("#cl-wrapper .menu-space .content").height();
          // var height = ($(document).height() < $(window).height())?$(window).height():menuH;
          //updateHeight();
         $("#cl-wrapper .nscroller").nanoScroller({ preventPageScrolling: true });
        });
        e.preventDefault();
      });
      
      /*Small devices toggle*/
      $(".cl-toggle").click(function(e){
        var ul = $(".cl-vnavigation");
        ul.slideToggle(300, 'swing', function () {
        });
        e.preventDefault();
      });
      
      /*Collapse sidebar*/
      $("#sidebar-collapse").click(function(){
          toggleSideBar();
      });
      
      
      if($("#cl-wrapper").hasClass("fixed-menu")){
        var scroll =  $("#cl-wrapper .menu-space");
        scroll.addClass("nano nscroller");
 
        function update_height(){
          var button = $("#cl-wrapper .collapse-button");
          var collapseH = button.outerHeight();
          var navH = $("#head-nav").height();
          var height = $(window).height() - ((button.is(":visible"))?collapseH:0) - navH;
          scroll.css("height",height);
          $("#cl-wrapper .nscroller").nanoScroller({ preventPageScrolling: true });
        }
        
        $(window).resize(function() {
          update_height();
        });    
            
        update_height();
        $("#cl-wrapper .nscroller").nanoScroller({ preventPageScrolling: true });
        
      }else{
        $(window).resize(function(){
          //updateHeight();
        }); 
        //updateHeight();
      }

      
      /*SubMenu hover */
        var tool = $("<div id='sub-menu-nav' style='position:fixed;z-index:9999;'></div>");
        
        function showMenu(_this, e){
          if(($("#cl-wrapper").hasClass("sb-collapsed") || ($(window).width() > 755 && $(window).width() < 963)) && $("ul",_this).length > 0){   
            $(_this).removeClass("ocult");
            var menu = $("ul",_this);
            if(!$(".dropdown-header",_this).length){
              var head = '<li class="dropdown-header">' +  $(_this).children().html()  + "</li>" ;
              menu.prepend(head);
            }
            
            tool.appendTo("body");
            var top = ($(_this).offset().top + 8) - $(window).scrollTop();
            var left = $(_this).width();
            
            tool.css({
              'top': top,
              'left': left + 8
            });
            tool.html('<ul class="sub-menu">' + menu.html() + '</ul>');
            tool.show();
            
            menu.css('top', top);
          }else{
            tool.hide();
          }
        }

        $(".cl-vnavigation li").hover(function(e){
          showMenu(this, e);
        },function(e){
          tool.removeClass("over");
          setTimeout(function(){
            if(!tool.hasClass("over") && !$(".cl-vnavigation li:hover").length > 0){
              tool.hide();
            }
          },500);
        });
        
        tool.hover(function(e){
          $(this).addClass("over");
        },function(){
          $(this).removeClass("over");
          tool.fadeOut("fast");
        });
        
        
        $(document).click(function(){
          tool.hide();
        });
        $(document).on('touchstart click', function(e){
          tool.fadeOut("fast");
        });
        
        tool.click(function(e){
          e.stopPropagation();
        });
     
        $(".cl-vnavigation li").click(function(e){
          if((($("#cl-wrapper").hasClass("sb-collapsed") || ($(window).width() > 755 && $(window).width() < 963)) && $("ul",this).length > 0) && !($(window).width() < 755)){
            showMenu(this, e);
            e.stopPropagation();
          }
        });
        
        $(".cl-vnavigation li").on('touchstart click', function(){
          //alert($(window).width());
        });
        
      $(window).resize(function(){
        //updateHeight();
      });

      var domh = $("#pcont").height();
      $(document).bind('DOMSubtreeModified', function(){
        var h = $("#pcont").height();
        if(domh != h) {
          //updateHeight();
        }
      });
      
      /*Return to top*/
      var offset = 220;
      var duration = 500;
      var button = $('<a href="#" class="back-to-top"><i class="fa fa-angle-up"></i></a>');
      button.appendTo("body");
      
      jQuery(window).scroll(function() {
        if (jQuery(this).scrollTop() > offset) {
            jQuery('.back-to-top').fadeIn(duration);
        } else {
            jQuery('.back-to-top').fadeOut(duration);
        }
      });
    
      jQuery('.back-to-top').click(function(event) {
          event.preventDefault();
          jQuery('html, body').animate({scrollTop: 0}, duration);
          return false;
      });
      
      /*Datepicker UI*/
      $( ".ui-datepicker" ).datepicker();
      
      /*Tooltips*/
      if(config.tooltip){
        $('.ttip, [data-toggle="tooltip"]').tooltip();
      }
      
      /*Popover*/
      if(config.popover){
        $('[data-popover="popover"]').popover();
      }

      /*NanoScroller*/      
      if(config.nanoScroller){
        $(".nscroller").nanoScroller();     
      }
      
      /*Nestable Lists*/
      if(config.nestableLists){
        $('.dd').nestable();
      }
      
      /*Switch*/
      if(config.bootstrapSwitch){
        $('.switch').bootstrapSwitch();
      }
      
      /*DateTime Picker*/
      if(config.dateTime){
        $(".datetime").datetimepicker({format: 'yyyy-mm-dd hh:ii'});
      }
      
      /*Select2*/
      if(config.select2){
         $(".select2").select2({
          width: '100%'
         });
      }
      
       /*Tags*/
      if(config.tags){
        $(".tags").select2({tags: 0,width: '100%'});
      }
      
       /*Slider*/
      if(config.slider){
        $('.bslider').slider();     
      }
      
      /*Input & Radio Buttons*/
      if(jQuery().iCheck){
        $('.icheck').iCheck({
          checkboxClass: 'icheckbox_square-blue checkbox',
          radioClass: 'iradio_square-blue'
        });
      }
      
      /*Bind plugins on hidden elements*/
      if(config.hiddenElements){
      	/*Dropdown shown event*/
        $('.dropdown').on('shown.bs.dropdown', function () {
          $(".nscroller").nanoScroller();
        });
          
        /*Tabs refresh hidden elements*/
        $('.nav-tabs').on('shown.bs.tab', function (e) {
          $(".nscroller").nanoScroller();
        });
      }
      
    },
      
    /*Pages Javascript Methods*/
    dashBoard: function (){
      dashboard();
    },
    
    speech: function(options){
      speech(options);
    },
    
    speechCommand: function(com, options){
      speechCommand(com, options);
    },
    
    toggleSideBar: function(){
      toggleSideBar();
    },
    
    uiElements: function(){
      uiElements();
    },
    
    nestableLists: function(){
      nestable();
    },
 
    wizard: function(){
      wizard();
    },
    
    masks: function(){
      masks();
    },
    
    textEditor: function(){
      textEditor();
    },
    
    dataTables: function(){
      dataTables();
    },
    
    maps: function(){
      maps();
    },
    
    charts: function(){
      charts();
    },
    
    widgets: function(){
      widgets();
    }
    
  };
 
}();

$(function(){
  //$("body").animate({opacity:1,'margin-left':0},500);
  $("body").css({opacity:1,'margin-left':0});
});

