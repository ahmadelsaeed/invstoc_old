var base_url2;
var base_url;
var _token;
var ajax_loader_img;
var modal_ajax_loader_img;
var ajax_loader_img_func;
var lang_url_class;
var extract_links_fun;
var getUrlVars;
var IsJsonString;
var show_flash_message;
var show_emotions;
var this_user_id;
var audio;

$(function(){

    base_url2 = $(".url_class").val();
    base_url = base_url2 + "/public_html/";
    base_url3 = $(".url_class_2").val();
    base_url_new = base_url3 + "/public_html/";
    _token = $(".csrf_input_class").val();
    lang_url_class = $(".lang_url_class").val()+"/";
    modal_ajax_loader_img="<img src='" + base_url_new + "img/new_loading.gif' class='ajax_loader_class' width='20'>";
    ajax_loader_img="<img src='" + base_url_new + "img/ajax-loader.gif' class='ajax_loader_class' width='20'>";
    ajax_loader_img_func=function(img_width){
        return "<img src='" + base_url_new + "img/ajax-loader.gif' class='ajax_loader_class' style='width:"+img_width+";height:"+img_width+";'>";
    };

    this_user_id = $(".this_user_id").val();
    audio = new Audio(base_url_new+'/front/sounds/notification_sound.mp3');


    extract_links_fun=function (text,match_site) {
        var links=[];

        var urlRegex = /(https?:\/\/[^\s]+)/g;
        text.replace(urlRegex, function(url) {
            if(url.includes(match_site)===true){
                links.push(url);
            }
            return "";
        });

        return links;
    }

    getUrlVars=function(link) {
        var vars = {};
        var parts = link.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
            vars[key] = value;
        });
        return vars;
    }

    IsJsonString=function (str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }

    show_flash_message=function (type,get_flash_message)
    {

        var pure_msg = '<b style="font-size: 20px;">' + get_flash_message + '</b>';

        toastr.options = {
            "closeButton": !1,
            "debug": !1,
            "newestOnTop": !0,
            "progressBar": !0,
            "positionClass": "toast-top-full-width",
            "preventDuplicates": !1,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "5000",
            "timeOut": "5000",
            "extendedTimeOut": "5000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        toastr[type](pure_msg, '', toastr.options);

    }


    show_emotions = function (this_element) {
        this_element.emojioneArea({
            pickerPosition: "bottom",
            /*saveEmojisAs : "shortname"*/
        });
    }


    /**
     * Start change_language
     */

    // $('body').on('click','.change_language',function () {
    //
    //     var this_element = $(this);
    //     var lang_id = this_element.attr("data-lang_id");
    //     var lang_title = this_element.attr('data-lang_title');
    //
    //     if(typeof lang_id != "undefined" && lang_id > 0)
    //     {
    //         lang_id = parseInt(lang_id);
    //
    //         var object = {};
    //         object._token = _token;
    //         object.lang_id = lang_id;
    //         object.lang_title = lang_title;
    //
    //         $.ajax({
    //             url: base_url2 + "/change_language",
    //             data: object,
    //             type: 'POST',
    //             success: function (data) {
    //                 // var return_data = JSON.parse(data);
    //
    //                 // location.reload();
    //             }
    //
    //         });
    //     }
    //
    //     return false;
    // });

    /**
     * End change_language
     */



    /**
     * Start change_book_language
     */

    $('body').on('click','.change_book_language',function () {

        var this_element = $(this);
        var lang_id = this_element.attr("data-lang_id");

        if(typeof lang_id != "undefined" && lang_id > 0)
        {
            lang_id = parseInt(lang_id);

            var object = {};
            object._token = _token;
            object.lang_id = lang_id;

            $.ajax({
                url: base_url2 + "/change_book_language",
                data: object,
                type: 'POST',
                success: function (data) {
                    // var return_data = JSON.parse(data);
                    location.reload();
                }

            });
        }

        return false;
    });

    /**
     * End change_book_language
     */


    /**
     * Start homepage_news_block
     */

        if($('.homepage_news_block').length)
        {

            setInterval(function () {

                $.each($('.homepage_news_block'),function (ind,val) {

                    if(!$(this).hasClass('hide_div'))
                    {
                        $(this).addClass('hide_div');

                        if($(this).next().length && $(this).next().hasClass('homepage_news_block'))
                        {
                            $(this).next().removeClass('hide_div');
                            return false;
                        }
                        else{
                            $('.homepage_news_block').first().removeClass('hide_div');
                            return false;
                        }
                        return false;
                    }

                });

            },5000);

        }

    /**
     * End homepage_news_block
     */


    /**
     * Start jssor slider config
     */

        jssor_slider1_starter = function (containerId) {
        var options = {
            $AutoPlay: 1,                                    //[Optional] Auto play or not, to enable slideshow, this option must be set to greater than 0. Default value is 0. 0: no auto play, 1: continuously, 2: stop at last slide, 4: stop on click, 8: stop on user navigation (by arrow/bullet/thumbnail/drag/arrow key navigation)
            $Idle: 4000,                            //[Optional] Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000
            $SlideDuration: 500,                                //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
            $DragOrientation: 3,                                //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $Cols is greater than 1, or parking position is not 0)
            $UISearchMode: 0,                                   //[Optional] The way (0 parellel, 1 recursive, default value is 1) to search UI components (slides container, loading screen, navigator container, arrow navigator container, thumbnail navigator container etc).

            $ThumbnailNavigatorOptions: {
                $Class: $JssorThumbnailNavigator$,              //[Required] Class to create thumbnail navigator instance
                $ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always

                $Loop: 1,                                       //[Optional] Enable loop(circular) of carousel or not, 0: stop, 1: loop, 2 rewind, default value is 1
                $SpacingX: 3,                                   //[Optional] Horizontal space between each thumbnail in pixel, default value is 0
                $SpacingY: 3,                                   //[Optional] Vertical space between each thumbnail in pixel, default value is 0
                $Cols: 6,                              //[Optional] Number of pieces to display, default value is 1
                $ParkingPosition: 253,                          //[Optional] The offset position to park thumbnail,

                $ArrowNavigatorOptions: {
                    $Class: $JssorArrowNavigator$,              //[Requried] Class to create arrow navigator instance
                    $ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
                    $AutoCenter: 2,                                 //[Optional] Auto center arrows in parent container, 0 No, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
                    $Steps: 6                                       //[Optional] Steps to go for each navigation request, default value is 1
                }
            }
        };

        var jssor_slider1 = new $JssorSlider$(containerId, options);

        //responsive code begin
        //you can remove responsive code if you don't want the slider scales while window resizing
        function ScaleSlider() {
            var parentWidth = jssor_slider1.$Elmt.parentNode.clientWidth;
            if (parentWidth)
                jssor_slider1.$ScaleWidth(Math.min(parentWidth, 550));
            else
                $Jssor$.$Delay(ScaleSlider, 30);
        }

        ScaleSlider();
        $Jssor$.$AddEvent(window, "load", ScaleSlider);

        $Jssor$.$AddEvent(window, "resize", ScaleSlider);
        $Jssor$.$AddEvent(window, "orientationchange", ScaleSlider);
        //responsive code end
    };

        if ($('.slider1_container').length)
        {
            jssor_slider1_starter('slider1_container');
        }

    /**
     * End jssor slider config
     */


});
