$(function () {

    $.fn.modal.Constructor.prototype.enforceFocus = function() {
        modal_this = this
        $(document).on('focusin.modal', function (e) {
            if (modal_this.$element[0] !== e.target && !modal_this.$element.has(e.target).length
                && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_select')
                && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_text')) {
                modal_this.$element.focus()
            }
        })
    };


    /**
     * Start add Page blocks
     */

        $('body').on('click','.remove_page_block_item',function () {

            if($('.page_block_item').length == 1)
            {
                alert("Can not Remove Last Item !!");
            }
            else{
                var confirm_msg = confirm('Are You Sure ?');
                if(confirm_msg)
                {
                    $(this).parents('.page_block_item').remove();
                }
            }

            return false;

        });

        $('body').on('click','.add_new_page_block_item',function () {

            var cloned_item = $('.page_block_item').first().clone();

            cloned_item.find('#cke_ck_0').remove();
            cloned_item.find('.book_page_block_id').remove();
            cloned_item.find('.preview_img').remove();

            $('.page_blocks_container').append(cloned_item);

            var input_length = $(".block_body_input").length;
            $.each($(".block_body_input"),function(ind,val){
                $(this).attr('id','ck_'+ind);
                console.log($(this).attr('id'));

                if($(this).hasClass("ckeditor") && (ind == (input_length-1))){
                    $('#cke_ck_'+ind).remove();
                    CKEDITOR.replace( $(this).attr('id') );
                }
            });


            return false;
        });

        $('body').on('click','.save_new_page',function () {

            if($('.block_img_file').length)
            {
                $.each($('.block_img_file'),function (ind,val) {
                    var old_name = $(this).attr('name');
                    $(this).attr('name',old_name+'_'+ind);
                });
            }

        });

        $('body').on('click','.save_page_and_exit',function () {

            if($('.redirect_page_type').length)
            {
                $('.redirect_page_type').val('exit');
            }

        });

        $('body').on('click','.save_page_and_new',function () {

            if($('.redirect_page_type').length)
            {
                $('.redirect_page_type').val('new');
            }

        });

        $('body').on('click','.remove_image_blow',function () {

            $(this).parent().remove();

        });

    /**
     * End add Page blocks
     */


    /**
     * Start Book Change Language
     */

    $('body').on('change','.select_book_lang',function () {

        if($('.book_id').length && $('.show_book_pages').length && $('.book_page_id').length && $('.book_page_number').length)
        {
            var this_element = $(this);
            var book_id = $('.book_id').val();
            var book_page_id = $('.book_page_id').val();
            var book_page_number = $('.book_page_number').val();
            var lang_id = this_element.val();

            this_element.attr("disabled","disabled");

            var object = {};
            object._token = _token;
            object.book_id = book_id;
            object.lang_id = lang_id;
            object.book_page_id = book_page_id;
            object.book_page_number = book_page_number;

            $.ajax({
                url: base_url2 + "/admin/books/show_book_pages",
                data: object,
                type: 'POST',
                success: function (data) {
                    console.log(data);
                    var json_data=JSON.parse(data);
                    this_element.removeAttr("disabled");
                    if(typeof (json_data)!="undefined"){
                        $(".show_book_pages").html(json_data.options);
                        $('.show_book_pages').select2();
                    }

                }

            });


            return false;
        }

    });

    /**
     * End Book Change Language
     */


    $('body').on('click','.delete_stock_exchange',function () {

        var confirm_msg = confirm("Are You Sure ? ");
        if(!confirm_msg)
        {
            return false;
        }

    });



    $("body").on("click",".general_remove_element",function () {

        var item = $(this).attr("data-removed_class");

        if (typeof (item) != "undefined" && item != "")
        {
            var confirm_res = confirm("Are You Sure?");
            if (confirm_res == true) {

                $(this).parents().find("."+item).remove();

            }//end confirmation if
        }

        return false;
    });

    $(".datepicker_input input").keydown(function(){
        return false;
    });


    $(".search_for_trip_title").keydown(function(e){

        if(e.which=="13"){
            var this_element = $(this);


            var object = {};
            object._token = _token;
            object.page_title = this_element.val();

            this_element.parent().append(ajax_loader_img_func("15px"));

            this_element.attr("disabled","disabled");

            $.ajax({
                url: base_url2 + "/admin/pages/search_for_page_name",
                data: object,
                type: 'POST',
                success: function (data) {
                    console.log(data);
                    var json_data=JSON.parse(data);
                    if(typeof (json_data)!="undefined"){
                        $(".select_trip").html(json_data.options);
                        this_element.removeAttr("disabled");
                        this_element.parent().children(".ajax_loader_class").remove();
                    }

                }

            });

            return false;
        }

    });

    $(".select_trip").change(function(){

        var this_element=$(this);
        var selected_option=$("option:checked",this_element);
        var trip_id=selected_option.attr("data-pageid");
        var trip_title=selected_option.attr("data-pagetitle");

        var is_exsit_before=$(".selected_trip[data-tripid='"+trip_id+"']");
        if(is_exsit_before.length==0&&typeof (trip_id)!="undefined"){

            var html="";
            html+='<label class="label label-success selected_trip" data-tripid="'+trip_id+'" style="font-size: 100%;">';
                html+=trip_title;
                html+='<a href="#" class="remove_related_trip">x</a>';
                html+='<input type="hidden" name="related_pages[]" value="'+trip_id+'">';
            html+="</label>";


            $(".selected_trips_div").append(html);
        }

    });

    $(".selected_trips_div").on("click",".remove_related_trip",function () {

        if(confirm("Are You Sure?")){
            $(this).parents(".selected_trip").remove();

        }

        return false;
    });


    $("#price_table_type_id").change(function(){

        var select_value=$(this).val();
        console.log(select_value);
        if (select_value=="type1") {
            $(".table_type").hide();
            $(".table_type1").show();
        }
        if (select_value=="type2") {
            $(".table_type").hide();
            $(".table_type2").show();
        }
        if (select_value=="type3") {
            $(".table_type").hide();
            $(".table_type3").show();
        }

    });


    /**
     * Start Manage Pages for Books
     */

    $('.add_new_page').click(function () {

        var this_element = $(this);
        var book_id = this_element.attr('data-book_id');
        var book_name = this_element.attr('data-book_name');

        if($('.load_pages_options').length)
        {
            $('.load_pages_options').html("");
        }

        // empty editors
        if($('textarea').length)
        {
            $.each($('textarea'),function (ind,val) {
                CKEDITOR.instances[$(this).attr('id')].setData("");
            });
        }

        if(typeof(book_id) != "undefined" && book_id > 0 && typeof(book_name))
        {

            $('#manage_pages').modal('show');
            $('#manage_pages').find('.get_book_name').html(book_name);
            $('.save_page_data').attr('data-book_id',book_id);
            $('.save_page_data_msg').html("");

            if(this_element.hasClass('load_old_pages'))
            {
                // get all pages trans
                $('.save_page_data').attr("disabled","disabled");
                var save_page_data_msg = this_element.parents().find('.save_page_data_msg');
                var object = {};
                object._token = _token;
                object.book_id = book_id;

                $.ajax({
                    url: base_url2 + "/admin/pages/load_book_pages",
                    data: object,
                    type: 'POST',
                    success: function (data) {

                        var json_data=JSON.parse(data);
                        console.log(json_data);
                        if(typeof (json_data)!="undefined" && typeof (json_data.options) !="undefined"
                            && json_data.options !="")
                        {

                            var select_html = "<div class='col-md-4 col-md-offset-4'>";
                                select_html += "<label><b>Select Your Page</b></label>";
                                select_html += "<select class='form-control select_page_number'>";
                                select_html += "<option></option>";
                                select_html += json_data.options;
                                select_html += "</select>";
                                select_html += "<input type='hidden' class='pages_trans_ids' value='"+json_data.trans_ids+"' >";
                            select_html += "</div>";

                            $('.load_pages_options').html(select_html);

                        }
                        else{
                            var error_msg = "<div class='alert alert-danger'> Not Found Book Pages exit and add new !!! </div>";
                            save_page_data_msg.html(error_msg);
                            return false;
                        }
                    }
                });
            }
            else{
                $('.save_page_data').removeAttr("disabled");
            }

        }

        return false;
    });

    $('body').on('change','.select_page_number',function () {

        var this_element = $(this);
        var save_page_data_msg = this_element.parents().find('.save_page_data_msg');
        var page_id = this_element.val();
        var object = {};
        object._token = _token;
        object.page_id = page_id;

        $.ajax({
            url: base_url2 + "/admin/pages/load_page_translates",
            data: object,
            type: 'POST',
            success: function (data) {

                var json_data=JSON.parse(data);
                console.log(json_data);
                if(typeof (json_data)!="undefined" && typeof (json_data.content_body) !="undefined"
                    && json_data.content_body !="")
                {

                    console.log(json_data.content_body);
                    $.each(json_data.content_body,function (ind,val) {
                        CKEDITOR.instances['page_body_'+val['lang_id']+'_id'].setData(val['page_body']);
                    });


                    $('.save_page_data').removeAttr("disabled");
                    $('.pages_trans_ids').val(page_id);

                }
                else{
                    var error_msg = "<div class='alert alert-danger'> Not Found Book Pages exit and add new !!! </div>";
                    save_page_data_msg.html(error_msg);
                    return false;
                }
            }
        });

        return false;
    });

    $('body').on('click','.save_page_data',function () {

        var this_element = $(this);
        var save_page_data_msg = this_element.parents().find('.save_page_data_msg');
        var book_id = this_element.attr('data-book_id');
        save_page_data_msg.html("");

        if(typeof(book_id) != "undefined" && book_id > 0) {

            this_element.append("<img src='" + base_url + "img/ajax-loader.gif' class='ajax_loader_class' width='20'>");
            this_element.attr("disabled", "disabled");

            var get_lang_ids = this_element.parents().find('.get_lang_ids').val();
            if(typeof(get_lang_ids) == "undefined" || get_lang_ids == "")
            {
                this_element.find('.ajax_loader_class').remove();
                this_element.removeAttr('disabled');
                var error_msg = "<div class='alert alert-danger'> Not Found Translation Data !!! </div>";
                save_page_data_msg.html(error_msg);
                return false;
            }
            get_lang_ids = get_lang_ids.split(',');
            console.log(get_lang_ids);

            var body_content = {};
            var body_flag = false;
            $.each(get_lang_ids,function (ind,val) {

                var this_body = CKEDITOR.instances['page_body_'+val+'_id'].getData();
                if(ind == 0 && (typeof(this_body) == "undefined" || this_body == ""))
                {
                    body_flag = true;
                    this_element.find('.ajax_loader_class').remove();
                    this_element.removeAttr('disabled');

                    var error_msg = "<div class='alert alert-danger'> First Page Body Can not be Empty !!! </div>";
                    save_page_data_msg.html(error_msg);
                    return false;
                }

                body_content['page_body_'+val] = this_body;

            });

            if(body_flag)
            {
                var error_msg = "<div class='alert alert-danger'> First Page Body Can not be Empty !!! </div>";
                save_page_data_msg.html(error_msg);
                return false;
            }

            var object = {};
            object.book_id = book_id;
            object.trans_id = 0;
            object.body_content = body_content;
            object._token = _token;

            if($('.pages_trans_ids').length)
            {
                object.trans_id = $('.pages_trans_ids').val();
            }

            $.ajax({
                url: base_url2 + "/admin/pages/save_book_page",
                data: object,
                type: 'POST',
                success: function (data) {
                    console.log(data);
                    var json_data=JSON.parse(data);
                    if(typeof (json_data)!="undefined"){
                        this_element.find('.ajax_loader_class').remove();
                        this_element.removeAttr('disabled');
                        save_page_data_msg.html(json_data.msg);
                    }
                }
            });

        }

        return false;
    });


    $('.order_pages').click(function () {

        var this_element = $(this);
        var book_id = this_element.attr('data-book_id');
        var book_name = this_element.attr('data-book_name');
        $('.modal_sorted_page_body').html("");

        $('.save_sorted_pages').removeAttr("disabled");

        if(typeof(book_id) != "undefined" && book_id > 0 && typeof(book_name))
        {

            $('#sort_pages_modal').modal('show');
            $('#sort_pages_modal').find('.get_sorted_book_name').html(book_name);
            $('.save_sorted_pages').attr('data-book_id',book_id);
            $('.save_sorted_pages_msg').html("");

            var object = {};
            object._token = _token;
            object.book_id = book_id;

            $.ajax({
                url: base_url2 + "/admin/pages/load_book_pages_li",
                data: object,
                type: 'POST',
                success: function (data) {

                    var json_data=JSON.parse(data);
                    console.log(json_data);
                    if(typeof (json_data)!="undefined" && typeof (json_data.items) !="undefined"
                        && json_data.items !="")
                    {

                        $('.modal_sorted_page_body').html(json_data.items);
                        $("#pages_sortable").sortable({
                            placeholder: "ui-state-highlight"
                        });
                        $( "#pages_sortable" ).disableSelection();
                    }
                    else{
                        var error_msg = "<div class='alert alert-danger'> Not Found Book Pages exit and add new !!! </div>";
                        $('.save_sorted_pages_msg').html(error_msg);
                        $('.save_sorted_pages').attr("disabled","disabled");
                        return false;
                    }
                }
            });

        }

        return false;
    });

    $('.save_sorted_pages').click(function () {

        var this_element = $(this);
        var book_id = this_element.attr('data-book_id');

        var items = [];

        if($('#pages_sortable li').length)
        {
            $.each($('#pages_sortable li'),function (ind,val) {
                items.push($(this).attr('data-item_id'));
            });

        }
        console.log(items);

        if(items.length)
        {
            this_element.append("<img src='" + base_url + "img/ajax-loader.gif' class='ajax_loader_class' width='20'>");
            this_element.attr("disabled", "disabled");

            var object = {};
            object._token = _token;
            object.book_id = book_id;
            object.items = items;

            $.ajax({
                url: base_url2 + "/admin/pages/order_pages_trans",
                data: object,
                type: 'POST',
                success: function (data) {

                    var json_data=JSON.parse(data);
                    this_element.removeAttr("disabled");
                    this_element.find('.ajax_loader_class').remove();
                    console.log(json_data);
                    if(typeof (json_data)!="undefined" && typeof (json_data.msg) !="undefined"
                        && json_data.msg !="")
                    {
                        $('.save_sorted_pages_msg').html(json_data.msg);
                    }
                    else{
                        var error_msg = "<div class='alert alert-danger'> Something is wrong !!! </div>";
                        $('.save_sorted_pages_msg').html(error_msg);
                        return false;
                    }
                }
            });
        }
        else{
            var error_msg = "<div class='alert alert-danger'> Not Found Items to Sort !!! </div>";
            $('.save_sorted_pages_msg').html(error_msg);
            $('.save_sorted_pages').attr("disabled","disabled");
            return false;
        }

        return false;


    });

    
    /**
     * End Manage Pages for Books
     */


    /**
     * Start Send Message & Notification
     */

        $('body').on('click','.open_change_code',function () {

            var this_element = $(this);
            var user_id = this_element.attr('data-user_id');
            var username = this_element.attr('data-username');

            if(typeof user_id != "undefined" && typeof username != "undefined"
                && username != "")
            {

                user_id = parseInt(user_id);
                if(user_id > 0)
                {
                    $('.change_code_modal').modal('show');
                    $('.change_code_modal .save_change_code').removeAttr('disabled');
                    $('.change_code_modal .save_change_code').attr('data-user_id',user_id);
                    $('.change_code_modal .user_new_code').val(username);
                }

            }

            return false;
        });


        $('body').on('click','.save_change_code',function () {

            var this_element = $(this);
            var user_id = this_element.attr('data-user_id');
            var username = $('.change_code_modal .user_new_code').val();

            if(typeof user_id != "undefined" && typeof username != "undefined"
            )
            {
                if(username != "")
                {
                    this_element.attr('disabled','disabled');

                    var object = {};
                    object._token = _token;
                    object.user_id = user_id;
                    object.username = username;

                    $.ajax({
                        url: base_url2 + "/admin/change_code",
                        data: object,
                        type: 'POST',
                        success: function (data) {

                            var json_data=JSON.parse(data);
                            this_element.removeAttr('disabled');

                            if(typeof (json_data) !="undefined" && typeof (json_data.msg) !="undefined"
                                && json_data.msg !="")
                            {

                                alert(json_data.msg);
                                this_element.parents().find('.show_msg_status').html(json_data.msg);
                            }
                        }
                    });
                }
                else{
                    alert('من فضلك ادخل الكود اولا !!');
                }

            }


            return false;
        });

    /**
     * End Send Message & Notification
     */


    /**
     * Start Change Code
     */

        $('body').on('click','.open_send_message',function () {

            var this_element = $(this);
            var user_id = this_element.attr('data-user_id');
            var full_name = this_element.attr('data-full_name');
            var type = this_element.attr('data-type');

            if(typeof user_id != "undefined" && typeof full_name != "undefined"
                && typeof type != "undefined")
            {

                user_id = parseInt(user_id);
                if(user_id > 0 && type != '')
                {
                    $('.message_notify_modal').modal('show');
                    $('.message_notify_modal .modal-title').text('Send Message to '+full_name);
                    $('.message_notify_modal .save_message_notify').removeAttr('disabled');
                    $('.message_notify_modal .save_message_notify').attr('data-user_id',user_id);
                    $('.message_notify_modal .save_message_notify').attr('data-type',type);
                    // $('.message_notify_modal .message_content').val('');
                    CKEDITOR.instances['message_content'].setData("");
                    $('.message_notify_modal .show_msg_status').html('');
                }

            }

            return false;
        });


        $('body').on('click','.save_message_notify',function () {

            var this_element = $(this);
            var user_id = this_element.attr('data-user_id');
            var type = this_element.attr('data-type');
            var message = CKEDITOR.instances['message_content'].getData();

            if(typeof user_id != "undefined" && typeof type != "undefined" &&
                typeof message != "undefined"
            )
            {

                if(message != "")
                {
                    console.log('asdasdasd');
                    this_element.attr('disabled','disabled');

                    var object = {};
                    object._token = _token;
                    object.user_id = user_id;
                    object.type = type;
                    object.message = message;

                    $.ajax({
                        url: base_url2 + "/admin/send_message",
                        data: object,
                        type: 'POST',
                        success: function (data) {

                            var json_data=JSON.parse(data);
                            console.log(json_data);
                            this_element.removeAttr('disabled');

                            if(typeof (json_data) !="undefined" && typeof (json_data.msg) !="undefined"
                                && json_data.msg !="")
                            {

                                alert(json_data.msg);
                                this_element.parents().find('.show_msg_status').html(json_data.msg);
                            }
                        }
                    });
                }
                else{
                    alert('من فضلك ادخل الرسالة اولا !!');
                }

            }


            return false;
        });

    /**
     * End Change Code
     */

});