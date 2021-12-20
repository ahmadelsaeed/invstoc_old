$(function () {


    /**
     * Start Search for users
     */

    $('.search_for_user').keyup(function (e) {

        var this_element = $(this);
        var parent_element=$(this).parents(".search_for_user_parent_div");


        var select_users_element = $('.load_search_users_results',parent_element);
        var send_message_btn_element = $('.send_users_message',parent_element);
        var message_content = $('.custom_message',parent_element);
        var current_val = this_element.val();

        // console.log(select_users_element);

        console.log(current_val);

        if(current_val.length >= 2 && typeof(select_users_element) != "undefined")
        {
            var object = {};
            object._token = _token;
            object.current_val = current_val;

            $.ajax({
                url: base_url2 + "/chat/search_for_users",
                data: object,
                type: 'POST',
                success: function (data) {

                    var json_data=JSON.parse(data);
                    if(typeof (json_data)!="undefined" && typeof(json_data.items) != "undefined"
                        && json_data.msg == ""){
                        if(json_data.items != "")
                        {
                            select_users_element.removeAttr("disabled");
                            select_users_element.removeClass("hide_input");
                            select_users_element.append(json_data.items);
                            var new_options = select_users_element.find('option');
                            var temp_arr = [];
                            $.each(new_options,function (ind ,val) {

                                var current_val = $(this).val();
                                if(temp_arr.length && $.inArray(current_val,temp_arr) != -1)
                                {
                                    $(this).remove();
                                }
                                else{
                                    temp_arr.push(current_val);
                                }

                            });
                            // select_users_element.select2(); // bk:

                            send_message_btn_element.removeAttr("disabled");

                        }
                        // else{
                        //     select_users_element.attr('disabled','disabled');
                        //     select_users_element.addClass('hide_input');
                        //     // select_users_element.html('');
                        //     send_message_btn_element.attr("disabled","disabled");
                        // }
                    }
                    else{
                        select_users_element.attr('disabled','disabled');
                        select_users_element.addClass('hide_input');
                        select_users_element.html('');
                        send_message_btn_element.attr("disabled","disabled");
                        alert(json_data.msg);

                    }

                }

            });


        }

        return false;
    });

    $('body').on('click','.messages_notification_link',function (e) {

        // e.stopPropagation();
        // return false;
    });

    /**
     * End Search for users
     */


    /**
     * Start Send Chat Message (on chat menu box)
     */


    $('body').on('click','.send_users_message',function () {

        var this_element = $(this);
        var select_users_element = this_element.parents().find('.load_search_users_results');
        var users = select_users_element.val();
        var message_content = this_element.parents().find('.custom_message');

        // console.log(users);
        // console.log(users.length);
        // console.log(message_content);

        if(typeof(select_users_element) != "undefined" && users.length > 0 &&
            typeof(message_content) != "undefined" && message_content.val() != "")
        {

            var object = {};
            object._token = _token;
            object.message_content = message_content.val();
            // users = JSON.stringify(users);
            object.users = [users]; // bk:

            this_element.attr('disabled','disabled');
            this_element.append(ajax_loader_img);

            $.ajax({
                url: base_url2 + "/chat/send_chat_message",
                data: object,
                type: 'POST',
                success: function (data) {

                    var json_data=JSON.parse(data);
                    if(typeof (json_data)!="undefined" && typeof(json_data.status) != "undefined"
                        && json_data.status == "success"){

                        var type = "success";
                        var get_flash_message = json_data.msg;
                        show_flash_message(type,get_flash_message);
                    }
                    else{
                        var type = "danger";
                        var get_flash_message = json_data.msg;
                        show_flash_message(type,get_flash_message);
                    }

                    this_element.removeAttr('disabled');
                    $('.ajax_loader_class').remove();
                }

            });

        }

        return false;
    });


    /**
     * End Send Chat Message
     */

    /**
     * Start Send Chat Message (on chat message page)
     */


    $('body').on('click','.send_chat_message_to_user',function () {

        var this_element = $(this);
        var chat_input_message_element = this_element.parents().find('.chat_input_message');
        var message = chat_input_message_element.val();
        var chat_id = this_element.attr('data-chat_id');
        chat_id = parseInt(chat_id);

        if(typeof(message) != "undefined" && message == "")
        {
            chat_input_message_element.css({'border':'1px solid red'});
        }

        if(typeof(message) != "undefined" && message != "" &&
            typeof(chat_id) != "undefined" && chat_id > 0)
        {

            var object = {};
            object._token = _token;
            object.chat_id = chat_id;
            object.message = message;

            chat_input_message_element.css({'border':'1px solid green'});
            this_element.attr('disabled','disabled');
            chat_input_message_element.attr('disabled','disabled');
            this_element.append(ajax_loader_img);

            $.ajax({
                url: base_url2 + "/chat/send_chat_message_to_user",
                data: object,
                type: 'POST',
                success: function (data) {

                    var json_data=JSON.parse(data);
                    if(typeof (json_data)!="undefined" && typeof(json_data.status) != "undefined"
                        && json_data.status == "success" && typeof (json_data.block)!="undefined"){

                        $('.append_to_chat').append(json_data.block);

                        var type = "success";
                        var get_flash_message = json_data.msg;
                        show_flash_message(type,get_flash_message);
                    }
                    else{
                        var type = "warning";
                        var get_flash_message = json_data.msg;
                        show_flash_message(type,get_flash_message);
                    }

                    this_element.removeAttr('disabled');
                    chat_input_message_element.removeAttr('disabled');
                    chat_input_message_element.val("");
                    $('.ajax_loader_class').remove();
                }

            });

        }

        return false;
    });


    /**
     * End Send Chat Message
     */

    /**
     * Start Chat with specific user
     */

        $('body').on('click','.send_custom_message_to_user',function () {
            
            var user_code = $(this).attr('data-user_code');
            if(typeof(user_code) != "undefined" && user_code != "")
            {
                if($('.messages_notification_link').length && $('.search_for_user').length)
                {
                    $('.messages_notification_link').mouseover();
                    $('.search_for_user').val(user_code);
                    $('.search_for_user').trigger('keyup');

                    setTimeout(function () {
                        var options = $('.load_search_users_results option');
                        if(options.length)
                        {
                            $.each(options,function (ind,val) {
                                console.log(ind);
                                if(ind == 0)
                                {
                                    $(this).attr("selected","selected");
                                }
                            });
                            $('.load_search_users_results').select2();
                        }

                    },1000);

                }

            }

            return false;
        });

    /**
     * End Chat with specific user
     */

    $('.messages_notification_link .not_alert_count').mouseover(function () {
        $(".messages_notification_link").mouseover();
    });

    $("body").on("mouseover click",'.messages_notification_link',function (e) {
        $(".messages_notification_link .fa-weixin").css("color","#000");

        var this_element = $(this);
        var chats_container = this_element.parents().find(".chats_container");

        var not_alert_count = this_element.find('.not_alert_count');
        if(not_alert_count.hasClass('hide_span') == false)
        {
            not_alert_count.addClass('hide_span');
        }
        not_alert_count.text('');

        var object = {};
        object._token = _token;


        if(chats_container.find('li').length == 0){
            chats_container.html(ajax_loader_img_func("15px"));

            $.ajax({
                url: base_url2 + "/chat/load_msgs",
                data: object,
                type: 'POST',
                success: function (data) {
                    console.log(data);
                    var json_data=JSON.parse(data);
                    if(typeof (json_data.msgs_html)!="undefined"){
                        chats_container.html(json_data.msgs_html);
                    }

                }

            });
        }

    });


    /**
     * Start click on more messages
     */

    $('.more_messages_page').click(function () {
        var link = $(this).attr('href');
        location.href = link;
    });

    /**
     * End click on more messages
     */

});