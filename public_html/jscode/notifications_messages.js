$(function(){
    var source;

    if (!!window.EventSource) {

        source = new EventSource(base_url2+'/get_updates');
        source.addEventListener('message', function(data) {

            if(IsJsonString(data.data)){
                var json_data=JSON.parse(data.data);


                if(typeof (json_data.notifications)!=undefined){
                    $.each(json_data.notifications,function(not_id,not_html){
                        if($(".not_block_"+not_id).length>0)return true;

                        $(".all_notifications_section").prepend(not_html);
                        $(".load_notifications_btn .fa-globe").css("color","red");

                        /**
                         * Start show notification count
                         */
                        var not_alert_count = $(".load_notifications_btn").find('.not_alert_count');
                        not_alert_count.removeClass('hide_span');

                        var current_val = not_alert_count.text();
                        if(current_val == "")
                        {
                            current_val = 0;
                        }
                        else{
                            current_val = parseInt(current_val);
                        }

                        current_val += 1;
                        not_alert_count.text(current_val);

                        /**
                         * End show notification count
                         */

                        var audio = new Audio(base_url2+'/public_html/front/sounds/notification_sound.mp3');
                        audio.play();
                    });
                }

                if(typeof (json_data.follow_notifications)!=undefined){
                    $.each(json_data.follow_notifications,function(not_id,not_html){
                        if($(".not_block_"+not_id).length>0)return true;

                        $(".all_follow_notifications_section").prepend(not_html);
                        $(".load_follow_notifications_btn .fa-user").css("color","red");

                        /**
                         * Start show notification count
                         */
                        var not_alert_count = $(".load_follow_notifications_btn").find('.not_alert_count');
                        not_alert_count.removeClass('hide_span');

                        var current_val = not_alert_count.text();
                        if(current_val == "")
                        {
                            current_val = 0;
                        }
                        else{
                            current_val = parseInt(current_val);
                        }

                        current_val += 1;
                        not_alert_count.text(current_val);

                        /**
                         * End show notification count
                         */

                        var audio = new Audio(base_url2+'/public_html/front/sounds/notification_sound.mp3');
                        audio.play();
                    });
                }

                if(typeof (json_data.msgs)!=undefined){
                    $.each(json_data.msgs,function(msg_id,msg_html){
                        if($(".msg_block_"+msg_id).length>0)return true;

                        $(".chats_container").prepend(msg_html);
                        $(".messages_notification_link .fa-weixin").css("color","red");

                        /**
                         * Start show notification count
                         */
                        var not_alert_count = $(".messages_notification_link").find('.not_alert_count');
                        not_alert_count.removeClass('hide_span');

                        var current_val = not_alert_count.text();
                        if(current_val == "")
                        {
                            current_val = 0;
                        }
                        else{
                            current_val = parseInt(current_val);
                        }

                        current_val += 1;
                        not_alert_count.text(current_val);

                        /**
                         * End show notification count
                         */

                        audio.play();
                    });
                }

                if(typeof (json_data.hot_orders)!=undefined){
                    $.each(json_data.hot_orders,function(order_id,order_html){
                        if($(".hot_order_"+order_id).length>0)return true;

                        console.log(order_html);

                        show_flash_message("info",order_html);
                        $(".hot_orders_ul").prepend(order_html);
                        $(".hot_orders_btn .fa-fire").css("color","#000");

                        audio.play();
                    });
                }

            }


        }, false);


    }

    $('.load_notifications_btn .not_alert_count').mouseover(function () {
        $(".load_notifications_btn").mouseover();
    });

    $("body").on("mouseover click",".load_notifications_btn",function(){

        var object = {};
        object._token = _token;

        var this_element = $(this);

        $(".load_notifications_btn .fa-globe").css("color","#000");


        if(this_element.attr("data-loaded")=="loaded"){
            return true;
        }

        var not_alert_count = this_element.find('.not_alert_count');
        if(not_alert_count.hasClass('hide_span') == false)
        {
            not_alert_count.addClass('hide_span');
        }

        this_element.attr("disabled","disabled");

        $(".all_notifications_section").html(ajax_loader_img_func("15px"));

        $.ajax({
            url: base_url2 + "/load_notifications",
            data: object,
            type: 'POST',
            success: function (data) {
                this_element.removeAttr("disabled");
                this_element.children(".ajax_loader_class").remove();

                var json_data=JSON.parse(data);
                if(typeof (json_data.notifications_html)!="undefined"){

                    $(".all_notifications_section").html(json_data.notifications_html);

                    this_element.attr("data-loaded","loaded");
                }
            }

        });

    });


    $('.load_follow_notifications_btn .not_alert_count').mouseover(function () {
        $(".load_follow_notifications_btn").mouseover();
    });

    $("body").on("mouseover click",".load_follow_notifications_btn",function(){

        var object = {};
        object._token = _token;

        var this_element = $(this);

        $(".load_follow_notifications_btn .fa-user").css("color","#000");


        if(this_element.attr("data-loaded") == "loaded"){
            return true;
        }

        var not_alert_count = this_element.find('.not_alert_count');
        if(not_alert_count.hasClass('hide_span') == false)
        {
            not_alert_count.addClass('hide_span');
        }

        this_element.attr("disabled","disabled");

        $(".all_follow_notifications_section").html(ajax_loader_img_func("15px"));

        $.ajax({
            url: base_url2 + "/load_follow_notifications",
            data: object,
            type: 'POST',
            success: function (data) {
                this_element.removeAttr("disabled");
                this_element.children(".ajax_loader_class").remove();

                var json_data=JSON.parse(data);
                if(typeof (json_data.notifications_html)!="undefined"){

                    $(".all_follow_notifications_section").html(json_data.notifications_html);

                    this_element.attr("data-loaded","loaded");
                }
            }

        });

    });


    $("body").on("click",".hot_orders_btn",function(){

        var object = {};
        object._token = _token;

        var this_element = $(this);

        $(".hot_orders_btn .fa-fire").css("color","");


        if(this_element.attr("data-loaded")=="loaded"){
            return true;
        }

        this_element.attr("disabled","disabled");

        $(".hot_orders_ul").append(ajax_loader_img_func("15px"));

        $.ajax({
            url: base_url2 + "/load_hot_orders",
            data: object,
            type: 'POST',
            success: function (data) {
                this_element.removeAttr("disabled");

                var json_data=JSON.parse(data);
                if(typeof (json_data.hot_orders_html)!="undefined"){
                    $(".hot_orders_ul").children(".ajax_loader_class").remove();

                    $(".hot_orders_ul").append(json_data.hot_orders_html);

                    this_element.attr("data-loaded","loaded");
                }
            }

        });

    });


    $('body').on('click','.not_alert_count',function () {
        return false;
    });

});