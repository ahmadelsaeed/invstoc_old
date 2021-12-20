jQuery(function ($) {

    if($('[data-toggle="tooltip"]').length)
    {
        setInterval(function () {
            $('[data-toggle="tooltip"]').tooltip();
        },800);
    }


    if($('.select_2_class').length){
        $('.select_2_class').select2();
    }

    if($(".datetimepicker_class_date").length>0){
        $(".datetimepicker_class_date").datetimepicker({format: 'YYYY-MM-DD'});
    }

    $('.prevent_form_submit_on_enter input').keydown(function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });

    $('body').on('click','.ck-button',function () {

    });

    $('body').on('click','.stop_close_dropdown',function (e) {
        e.stopPropagation();
    });

    $('body').on('click','.events_notifications_container',function (e) {
        e.stopPropagation();
    });

    $('body').on('click','.dropdown-menu .allow_link',function (e) {
        location.href=$(this).attr("href");
    });


    /**
     * Start select_post_tab_type
     */

        $('body').on('click','.select_post_tab_type',function () {

            var this_element = $(this);

            var type = this_element.attr('data-type');
            if (type != undefined && type != "")
            {
                this_element.parents('.parent_add_post_div').find('.post_or_recommendation_class').val(type);

                if (type == "post")
                {
                    this_element.parents('.parent_add_post_div').find('.post_recommendation_options').hide();
                }
                else{
                    this_element.parents('.parent_add_post_div').find('.post_recommendation_options').show();
                }

            }

            $('.select_post_tab_type').removeClass('active show');
            this_element.addClass('active show');

            return false;
        });

    /**
     * End select_post_tab_type
     */




    /**
     * Start stock_exchange_filter
     */

        $('body').on('click','.stock_exchange_filter a',function () {

            var this_element = $(this);
            var filter_target = this_element.attr('id');

            this_element.parents('form').find('.filter_target').val(filter_target);

            if(filter_target == "range_date")
            {
                if(!$('.range_date_container').hasClass('hide_div'))
                {
                    $('.range_date_container').addClass('hide_div');
                }
                else{
                    $('.range_date_container').removeClass('hide_div');
                }
            }
            else{
                this_element.parents('form').submit();
            }

            return false;
        });

        $('body').on('click','.search_stock_range a',function () {

            var this_element = $(this);
            this_element.parents('form').submit();

            return false;
        });

    /**
     * End stock_exchange_filter
     */



    /**
     * Start Load More Books
     */

        $('body').on('click','.load_more_books',function () {

            var this_element = $(this);
            var offset = $('.book_item').length;
            var cat_id = this_element.attr("data-cat_id");

            if(typeof(cat_id) != "undefined")
            {
                var object = {};
                object._token = _token;
                object.offset = offset;
                object.cat_id = cat_id;
                if(this_element.attr('data-target_search') != "undefined")
                {
                    object.target_search = this_element.attr('data-target_search');
                }

                this_element.attr('disabled','disabled');
                this_element.append(ajax_loader_img_func("15px"));

                $.ajax({
                    url: base_url2 + "/load_more_books",
                    data: object,
                    type: 'POST',
                    success: function (data) {
                        this_element.removeAttr("disabled");
                        this_element.children(".ajax_loader_class").remove();
                        var json_data=JSON.parse(data);
                        if(typeof (json_data)!="undefined" && typeof(json_data.items) != "undefined" && json_data.msg == ""){
                            $('.get_uploaded_items').append(json_data.items);
                            if(json_data.items == "")
                            {
                                this_element.remove();
                            }
                        }
                        else{
                            alert(json_data.msg);
                        }

                    }

                });

            }



            return false;
        });

    /**
     * End Load More Books
     */


    /**
     * Start Trade Calculations on Brokers left sidebar page
     */

        $('.broker_id').change(function () {

            var this_element = $(this);
            var select_pairs_element = this_element.parents().find('.pair_currency_id');
            var load_trade_message = this_element.parents().find('.load_trade_message');
            var broker_id = this_element.val();

            load_trade_message.html("");

            if(typeof(broker_id) != "undefined" && broker_id > 0)
            {
                var object = {};
                object._token = _token;
                object.broker_id = broker_id;

                $.ajax({
                    url: base_url2 + "/load_broker_currencies",
                    data: object,
                    type: 'POST',
                    success: function (data) {

                        var json_data=JSON.parse(data);
                        if(typeof (json_data)!="undefined" && typeof(json_data.items) != "undefined"
                            && json_data.msg == ""){
                            select_pairs_element.removeAttr("disabled");
                            select_pairs_element.html(json_data.items)
                        }
                        else{
                            alert(json_data.msg);
                        }

                    }

                });

            }
            else{
                select_pairs_element.attr("disabled","disabled");
                select_pairs_element.html("<option>Choose Broker at First</option>");
            }

            return false;
        });

        $('.get_trade_sum').click(function () {

            var this_element = $(this);
            var load_trade_message = this_element.parents().find('.load_trade_message');
            var broker_id = this_element.parents().find('.broker_id').val();
            if(typeof(broker_id) == "undefined" || broker_id == 0)
            {
                this_element.parents().find('.pair_currency_id').attr("disabled","disabled");
                alert(" Please Select Broker !! ");
                return false;
            }
            else{
                this_element.parents().find('.pair_currency_id').removeAttr("disabled");
            }

            var pair_currency_id = this_element.parents().find('.pair_currency_id').val();
            if(typeof(pair_currency_id) == "undefined" || pair_currency_id == 0)
            {
                alert(" Please Select Pair of Currency to selected Broker !! ");
                return false;
            }

            var trade_volume = this_element.parents().find('.trade_volume').val();
            if(typeof(trade_volume) == "undefined" || trade_volume == 0)
            {
                alert(" Please Enter Trade Volume at First !! ");
                return false;
            }
            var object = {};
            object._token = _token;
            object.broker_id = broker_id;
            object.pair_currency_id = pair_currency_id;
            object.trade_volume = trade_volume;

            $.ajax({
                url: base_url2 + "/calc_broker_trade",
                data: object,
                type: 'POST',
                success: function (data) {

                    var json_data=JSON.parse(data);
                    load_trade_message.html(json_data.msg)
                }

            });

            return false;
        });

    /**
     * End Trade Calculations on Brokers left sidebar page
     */


    /**
     * Start Stock Exchange Page
     */

    $('.select_group_by').change(function () {
        $(this).parents('form').submit();
    });

    $('.change_timezone').change(function () {
        $(this).parents('form').submit();
    });

    /**
     * End Stock Exchange Page
     */


    /**
     * Start Search Friends Page
     */

    $('.search_for_friends').keyup(function (event) {
        if (event.which==13) {
            var search_val = $('.search_for_friends').val();
            if(search_val != "" && search_val.length > 0)
            {
                $(this).parents('form').submit();
            }
            else{
                alert("Please Enter keywords to search for friends");
            }
        }
    });

    /**
     * End Search Friends Page
     */


    /**
     * Start Book Go to Page
     */

    $('.get_page_number').keyup(function (event) {
        if (event.which==13) {
            if ($('.get_page_number').length > 0)
            {
                var page_number = $('.get_page_number').val();
                if(page_number > 0)
                {
                    $('.get_page_btn').click();
                }
                else{
                    alert("Please Enter Valid Book Page Number !!");
                }
            }

        }
    });

    $('.get_page_btn').click(function () {

        if ($('.get_page_number').length > 0)
        {
            var page_number = $('.get_page_number').val();
            var book_id = $('.get_page_number').attr('data-book_id');
            if(page_number > 0 && book_id > 0)
            {
                var url = base_url2+'/books/'+book_id+'?page='+page_number;
                location.href = url;
            }
            else{
                alert("Please Enter Valid Book Page Number !!");
            }
        }

    });

    /**
     * End Book Go to Page
     */


    /**
     * Start Get Events
     */

    // get_next_events();
    // var today_events = Cookies.get('today_events');
    // console.log(today_events);
        setInterval(function () {

            var today_events = localStorage.getItem('today_events');
            if(today_events != null && typeof(today_events) != undefined && today_events != "")
            {

                today_events = JSON.parse(today_events);

                var current_datetime = $('.current_datetime').val();
                if(current_datetime > today_events.events.target_datetime)
                {
                    get_next_events();
                }

            }
            else{
                get_next_events();
            }

            if(today_events != null && typeof(today_events.events) != "undefined" && typeof(today_events.events.events) != "undefined" &&
                today_events.events.events.length > 0 && $('.events_stock_table').html() == "" )
            {

                var table_data = '<div class="col-md-12 table-note">';
                table_data += '<table class="table table-striped">';

                table_data += '<thead>';
                    table_data += '<tr>';
                        table_data += '<th>'+today_events.events_keywords.time_label+'</th>';
                        table_data += '<th>'+today_events.events_keywords.currency_label+'</th>';
                        table_data += '<th>'+today_events.events_keywords.importance_label+'</th>';
                        table_data += '<th>'+today_events.events_keywords.event_label+'</th>';
                        table_data += '<th>'+today_events.events_keywords.current_label+'</th>';
                        table_data += '<th>'+today_events.events_keywords.expected_label+'</th>';
                        table_data += '<th>'+today_events.events_keywords.previous_label+'</th>';
                    table_data += '</tr>';
                table_data += '</thead>';
                table_data += '<tbody>';

                $.each(today_events.events.events,function (ind ,val) {
                    table_data += '<tr>';
                        table_data += '<td class="event_datetime_val">'+val.event_datetime+'</td>';
                        table_data += '<td>'+val.cur_to+'</td>';
                        table_data += '<td>'+'<img src="'+base_url+'front/images/levels/'+val.importance_degree+'.png" width="25" height="25">'+'</td>';
                        table_data += '<td>'+val.page_title+'</td>';
                        table_data += '<td>'+val.current_value+'</td>';
                        table_data += '<td>'+val.expected_value+'</td>';
                        table_data += '<td>'+val.previous_value+'</td>';
                    table_data += '</tr>';

                });

                table_data +='</tbody>';
                table_data +='</table>';
                table_data +='<a href="'+base_url2+'/economic_calendar">'+today_events.general_static_keywords.more+'</a> ';
                table_data +='</div>';

                $('.events_stock_table').html(table_data);


                // $('.show_comming_events').click();
                // $('.show_comming_events').css({'background-color':'#fff','color':'#000'});
                //
                // var audio = new Audio(base_url2+'/public_html/front/sounds/notification_sound.mp3');
                // audio.play();

            }

        },5000);


        setInterval(function () {

            var current_datetime = new Date();
            if($('.event_datetime_val').length)
            {
                $.each($('.event_datetime_val'),function (ind,val) {

                    var this_element = $(this);
                    var this_datetime = this_element.text();
                    // console.log(this_datetime);
                    this_datetime = new Date(this_datetime);

                    var diff_time = (this_datetime - current_datetime);

                    // console.log(this_datetime);
                    // console.log(diff_time);


                    // 15 min (15*60*1000)
                    if(diff_time <= 900000 && diff_time >= 310000)
                    {
                        // console.log('15 min');
                        fire_events_notifications_with_focus(this_element,'#73BBD2','#fff','15');
                    }
                    // 5 min (5*60*1000)
                    else if(diff_time <= 300000 && diff_time >= 61000)
                    {
                        // console.log('5 min');
                        fire_events_notifications_with_focus(this_element,'#73d27a','#fff','5');
                    }
                    // current min (1*60*1000)
                    else if(diff_time <= 60000 && diff_time >= 1000)
                    {
                        // console.log('1 min');
                        fire_events_notifications_with_focus(this_element,'green','#fff','1');
                    }
                    //
                    else if(diff_time < 1000)
                    {
                        // console.log('-1 min');
                        fire_events_notifications_with_focus(this_element,'#F2DEDE','#000','0');
                    }

                });
            }

        },2000);

        function get_next_events () {
            $.ajax({
                url: base_url2 + "/economic_calendar/events",
                data: {"_token": _token},
                type: 'POST',
                success: function (data) {
                    var return_data = JSON.parse(data);
                    console.log(return_data);

                    if(typeof(return_data.events) != "undefined" && return_data.events != "")
                    {
                        localStorage.setItem('today_events',data);

                        localStorage.removeItem('alert_time_15');
                        localStorage.removeItem('alert_time_5');
                        localStorage.removeItem('alert_time_1');
                        localStorage.removeItem('alert_time_0');
                        // Cookies.set('today_events', return_data.events, { expires: 1 });
                    }
                }

            });

        }

        function fire_events_notifications_with_focus(this_element,color,text_color,alert_time) {
            $('.show_comming_events').css({'background-color':'#fff','color':'#000'});
            this_element.parent().css({'background-color':color,'color':text_color});


            var alert_time_not = localStorage.getItem('alert_time_'+alert_time);
            if(alert_time_not != null && typeof(alert_time_not) != undefined && alert_time_not != "") {

            }
            else{
                $('.show_comming_events').click();
                $('.show_comming_events').css({'background-color':'#fff','color':'#000'});

                var audio = new Audio(base_url2+'/public_html/front/sounds/notification_sound.mp3');
                audio.play();

                localStorage.setItem('alert_time_'+alert_time,'true');
            }


        }

    /**
     * End Get Events
     */


    /**
     * Start Cash back
     */

        $('body').on('click','.add_new_account',function () {

            var this_element = $(this);
            var account_number_element = this_element.parents().find('.account_number');
            var company_select_element = this_element.parents().find('[name="page_id"]');


            if(typeof(company_select_element) == "undefined" || !(company_select_element.val() > 0))
            {
                company_select_element.css({'border':'1px solid red'});
                return false
            }
            else{
                company_select_element.css({'border':'1px solid green'});
            }

            if(typeof(account_number_element) == "undefined" || account_number_element.val() == "")
            {
                account_number_element.css({'border':'1px solid red'});
                return false
            }
            else{
                account_number_element.css({'border':'1px solid green'});
            }

            this_element.attr("disabled","disabled");

            var object = {};
            object._token = _token;
            object.page_id = company_select_element.val();
            object.account_number = account_number_element.val();
            object.account_id = 0;
            if(typeof(this_element.attr("data-account_id")) != "undefined")
            {
                object.account_id = this_element.attr("data-account_id");
            }

            $.ajax({
                url: base_url2 + "/add_new_account_to_cash_back",
                data: object,
                type: 'POST',
                success: function (data) {
                    var return_data = JSON.parse(data);
                    this_element.removeAttr("disabled");

                    if(typeof(return_data.status) != "undefined" && return_data.status == "success")
                    {
                        alert(return_data.msg);
                        my_cash_back_accounts();
                        $('.account_number').val("");
                        $('.add_new_account').attr("data-account_id",0);
                    }
                    else{
                        alert(return_data.msg)
                    }
                }

            });


            return false;
        });


        $('body').on('click','.edit_account_data',function () {

            var this_element = $(this);
            var page_title = this_element.attr("data-page_title");
            var page_id = this_element.attr("data-page_id");
            var account_number = this_element.attr("data-account_number");
            var id = this_element.attr("data-id");

            if(
                typeof(page_title) != "undefined" && page_title != "" &&
                typeof(page_id) != "undefined" && page_id > 0 &&
                typeof(account_number) != "undefined" && account_number != "" &&
                typeof(id) != "undefined" && id > 0
            )
            {

                var option_item = '<option value="'+page_id+'" selected>'+page_title+'</option>';
                $('.add_new_account_body').show();

                $('.load_other_companies').html(option_item);
                $('.account_number').val(account_number);
                $('.add_new_account').attr("data-account_id",id);

            }
            else{
                alert("Something is Wrong !!");
            }


            return false;
        });


        $('.get_my_cash_back_accounts').click(function () {

            var this_element = $(this);
            $('.add_new_account_body').show();
            my_cash_back_accounts();
            $('.account_number').val("");
            $('.add_new_account').attr("data-account_id",0);

            $('.total_cash_back_balance').html("0");
            $('.request_to_withdraw').removeAttr('disabled');

        });

        $('body').on('change','.check_account_item',function () {
            calculate_cashback_balance();
        });

        $('body').on('change','.check_all_account_items',function () {

            var this_element = $(this);
            var all_checked=$(this).is(":checked");

            var items = $('.check_account_item');

            if(typeof(items) != "undefined" && items.length)
            {
                if(!all_checked)
                {
                    items.prop('checked', false);
                }
                else{
                    items.prop('checked','checked');
                }
            }

            calculate_cashback_balance();

            return false;
        });

        $('body').on('click','.request_to_withdraw',function () {

            var this_element = $(this);
            var items = $('.check_account_item');
            var account_ids = [];

            if(typeof(items) == "undefined" || items.length == 0)
            {
                alert("لا يوجد حسابات أضف في الاول !!");
                return false;
            }

            console.log(items);
            $.each(items,function (ind,val) {
                if($(this).is(":checked"))
                {
                    account_ids.push($(this).attr('data-account_id'));
                }
            });
            console.log(account_ids);

            if(account_ids.length == 0)
            {
                alert("علم علي الحسابات التي تريد طلب السحب لها !!");
                return false;
            }

            this_element.attr("disabled","disabled");

            var object = {};
            object._token = _token;
            object.account_ids = account_ids;
            $.ajax({
                url: base_url2 + "/request_accounts_withdraw",
                data: object,
                type: 'POST',
                success: function (data) {
                    var return_data = JSON.parse(data);
                    this_element.removeAttr("disabled");

                    if(typeof(return_data.msg) != "undefined")
                    {
                        alert(return_data.msg);
                    }
                }

            });

            return false;
        });

        function calculate_cashback_balance()
        {
            var account_items = $('.check_account_item');
            var wanted_balance = 0;

            $.each(account_items,function (ind,val) {
                if($(this).is(":checked"))
                {
                    var current_balance = $(this).attr('data-balance');
                    if(typeof current_balance != "undefined")
                    {
                        current_balance = parseFloat(current_balance);
                        wanted_balance += current_balance;
                    }
                }
            });

            $('.total_cash_back_balance').html(wanted_balance);
        }

        function my_cash_back_accounts() {

            if($('.check_all_account_items').length)
            {
                $('.check_all_account_items').prop("checked",false);
            }

            $('.my_cash_back_accounts').html(ajax_loader_img_func("15px"));

            $.ajax({
                url: base_url2 + "/get_my_cash_back_accounts",
                data: {'_token':_token},
                type: 'POST',
                success: function (data) {
                    var return_data = JSON.parse(data);

                    if(typeof(return_data.status) != "undefined" && return_data.status == "success")
                    {
                        $('.my_cash_back_accounts').html(return_data.data);
                        if (typeof(return_data.select_items) != "undefined" && return_data.select_items != "")
                        {
                            $('.add_new_account_body').find('.load_other_companies').html(return_data.select_items);
                        }
                        else{
                            $('.add_new_account_body').hide();
                        }
                    }
                }

            });
        }

    /**
     * End Cash back
     */


    /**
     * Start Workshops trending
     */

        $('.workshops_trending_hover').click(function () {
            var this_element = $(this);
            var object = {};
            object._token = _token;

            var ul=$(".work-shop",$($(this).data("target")));

            if(!this_element.find("ul").find("li").length)
            {
                ul.html(ajax_loader_img_func("15px"));
                $.ajax({
                    url: base_url2 + "/get_workshops_trending",
                    data: object,
                    type: 'POST',
                    success: function (data) {
                        var return_data = JSON.parse(data);

                        if(typeof(return_data.status) != "undefined" && return_data.status == "success")
                        {
                            ul.html(return_data.data);
                        }
                    }

                });
            }

        });

    /**
     * End Workshops trending
     */

    /**
     * Start Books trending
     */

        $('.books_trending_hover').click(function () {

            var this_element = $(this);
            var object = {};
            object._token = _token;

            var ul=$(".work-shop",$($(this).data("target")));

            if(!ul.find("li").length)
            {
                ul.html(ajax_loader_img_func("15px"));
                $.ajax({
                    url: base_url2 + "/get_books_trending",
                    data: object,
                    type: 'POST',
                    success: function (data) {
                        var return_data = JSON.parse(data);

                        if(typeof(return_data.status) != "undefined" && return_data.status == "success")
                        {
                            ul.html(return_data.data);
                        }
                    }

                });
            }

        });

    /**
     * End Books trending
     */


    /**
     * Start Brokers trending
     */

        $('.brokers_trending_hover').click(function () {

            var this_element = $(this);
            var object = {};
            object._token = _token;

            var ul=$(".work-shop",$($(this).data("target")));

            if(!ul.find("li").length)
            {
                ul.html(ajax_loader_img_func("15px"));
                $.ajax({
                    url: base_url2 + "/get_brokers_trending",
                    data: object,
                    type: 'POST',
                    success: function (data) {
                        var return_data = JSON.parse(data);

                        if(typeof(return_data.status) != "undefined" && return_data.status == "success")
                        {
                            ul.html(return_data.data);
                        }
                    }

                });
            }

        });

    /**
     * End Brokers trending
     */


    /**
     * Start Users trending
     */

        $('.users_trending_hover').click(function () {

            var this_element = $(this);
            var object = {};
            object._token = _token;
            var ul=$(".work-shop",$($(this).data("target")));


            if(!ul.find("li").length)
            {
                ul.html(ajax_loader_img_func("15px"));

                $.ajax({
                    url: base_url2 + "/get_users_trending",
                    data: object,
                    type: 'POST',
                    success: function (data) {
                        var return_data = JSON.parse(data);

                        if(typeof(return_data.status) != "undefined" && return_data.status == "success")
                        {
                            ul.html(return_data.data);
                        }
                    }

                });
            }

        });

    /**
     * End Users trending
     */


    /**
     * Start accept & remove group requests
     */

        $('.accept_group_request').click(function () {

        var this_element = $(this);
        var group_id = this_element.attr('data-group_id');
        var user_id = this_element.attr('data-user_id');

        if(
            typeof group_id != "undefined" && group_id > 0 &&
            typeof user_id != "undefined" && user_id > 0
        )
        {

            this_element.attr("disabled","disabled");

            var object = {};
            object._token = _token;
            object.group_id = group_id;
            object.user_id = user_id;


            $.ajax({
                url: base_url2 + "/group/accept_request_join",
                data: object,
                type: 'POST',
                success: function (data) {
                    var return_data = JSON.parse(data);
                    this_element.removeAttr("disabled");
                    if(typeof(return_data.status) != "undefined" && return_data.status == "success")
                    {
                        this_element.parents("tr").remove();
                    }
                    else{
                        alert(return_data.msg)
                    }
                }

            });
        }

        return false;
    });

        $('.remove_group_request').click(function () {

        var this_element = $(this);
        var group_id = this_element.attr('data-group_id');
        var user_id = this_element.attr('data-user_id');

        if(
            typeof group_id != "undefined" && group_id > 0 &&
            typeof user_id != "undefined" && user_id > 0
        )
        {

            this_element.attr("disabled","disabled");

            var object = {};
            object._token = _token;
            object.group_id = group_id;
            object.user_id = user_id;


            $.ajax({
                url: base_url2 + "/group/remove_request_join",
                data: object,
                type: 'POST',
                success: function (data) {
                    var return_data = JSON.parse(data);
                    this_element.removeAttr("disabled");
                    if(typeof(return_data.status) != "undefined" && return_data.status == "success")
                    {
                        this_element.parents("tr").remove();
                    }
                    else{
                        alert(return_data.msg)
                    }
                }

            });
        }

        return false;

    });

    /**
     * End accept & remove group requests
     */


    /**
     * Start load more text
     */

    $('body').on('click','.load_more_paragraph',function () {

        var this_element = $(this);
        var full_body_element = this_element.parents('p');
        if(typeof full_body_element != "undefined")
        {
            var full_body = full_body_element.attr('data-full_body');
            if(typeof full_body != "undefined" && full_body != "")
            {
                full_body_element.html(full_body);
            }
        }

        return false;
    });

    /**
     * End load more text
     */


    /**
     * Start chat_div_link
     */

        $('body').on('click','.block_div_link',function () {

            var this_element = $(this);
            var full_url = this_element.attr('data-full_url');
            if(typeof full_url != "undefined" && full_url != "")
            {
                location.href = full_url;
            }

        });

    /**
     * End chat_div_link
     */





    /**
     * Start Not Used Js until now
     */

    // Start Subscribe

    $(".subscribe_submit_email").keyup(function(event){

        if (event.which==13) {
            $(".subscribe_submit_btn").click();
        }

    });

    $(".subscribe_submit_btn").click(function(){

        var email_element=$(".subscribe_submit_email");

        var this_element = $(this);
        var email = email_element.val();

        email_element.attr("disabled","disabled");
        this_element.attr("disabled","disabled");

        if (typeof (email) == "undefined" || email == "") {
            $(".subscribe_submit_email").css({"border": "2px solid red"});
            this_element.removeAttr("disabled");
            email_element.removeAttr("disabled");
        } else {

            $.ajax({
                url: base_url2 + lang_url_class + "subscribe_contact/subscribe",
                data: {"_token": _token, "email": email},
                type: 'POST',
                success: function (data) {
                    var return_data = JSON.parse(data);
                    if (typeof (return_data.error) != "undefined" && return_data.error != "error") {
                        $(".subscribe_submit_email").css({"border": "2px solid green"});
                        $(".subscribe_msg").html("");
                    } else {
                        $(".subscribe_submit_email").css({"border": "2px solid red"});
                        $(".subscribe_msg").html(return_data.error_msg.email);
                        //$(".ajax_loader_class").remove();
                        this_element.removeAttr("disabled");
                        email_element.removeAttr("disabled");
                    }
                }

            });
        }

        return false;
    });

    // End Subscribe

    // Start Support
    $("body").on("click", ".contact_us_btn", function () {

        var parent_div=$(this).parents(".contact_us_parent_div");

        var object = {};
        object.name = $("#name",parent_div).val();
        object.tel = $("#phone",parent_div).val();
        object.email = $("#email",parent_div).val();
        object.title = $("#title",parent_div).val();
        object.msg_type = $("#msg_type",parent_div).val();
        object.message = $("#msg",parent_div).val();

        object.address = $("#address",parent_div).val();
        object.fax = $("#fax",parent_div).val();

        object.arrival_date = $("#arrival_date",parent_div).val();
        object.departure_date = $("#departure_date",parent_div).val();
        object.adults_number = $("#adults_number",parent_div).val();
        object.children_number = $("#children_number",parent_div).val();


        object.trip_id = $("#check_availability_trip_id",parent_div).val();

        object.current_url = location.href;
        // object.subscribe_option = $("#subscribe_option_id").is(":checked");
        object._token = _token;

        var this_element = $(this);
        this_element.append(ajax_loader_img_func("15px"));

        this_element.attr("disabled","disabled");

        $.ajax({
            url: base_url2 + "/subscribe_contact/make_a_contact",
            data: object,
            type: 'POST',
            success: function (data) {
                var json_data=JSON.parse(data);
                if(typeof (json_data)!="undefined"){
                    $(".display_msgs",parent_div).html(json_data.msg);
                    this_element.removeAttr("disabled");
                    this_element.children(".ajax_loader_class").remove();
                }

            }

        });
        return false;
    });
    // End Support


    /**
     * Start add_to_orders_list
     */

        $('body').on('click','.add_to_orders_list',function () {

            var this_element = $(this);
            var post_id = this_element.attr('data-postid');
            post_id = parseInt(post_id);

            var is_clicked = this_element.attr('data-clicked');

            if(typeof is_clicked != "undefined")
            {
                $('.orders_list_btn').click();
                return false;
            }


            if(post_id > 0)
            {

                this_element.attr('data-clicked','true');
                this_element.append(' '+ajax_loader_img+' ');
                var object = {};
                object._token = _token;
                object.post_id = post_id;

                $.ajax({
                    url: base_url2 + "/add_to_orders_list",
                    data: object,
                    type: 'POST',
                    success: function (data) {

                        this_element.find('.ajax_loader_class').remove();

                        var json_data=JSON.parse(data);
                        if(
                            typeof (json_data.msg) != "undefined" && typeof json_data.status != "undefined" &&
                            typeof json_data.li_item != "undefined"
                        ){

                            if(json_data.status == "success" && json_data.li_item != "")
                            {


                                var check_is_loaded = $('.orders_list_btn').attr('data-clicked');

                                if(typeof check_is_loaded != "undefined")
                                {
                                    $('.orders_list_ul').append(json_data.li_item);
                                }

                                $('.make_orders_list_post').removeAttr('disabled');
                                $('.make_orders_list_post').parents().find('.orders_list_post_text').removeClass('hide_div');

                                show_flash_message("success",json_data.msg);

                                if($('.orders_list_items').length)
                                {
                                    var items_count = $('.orders_list_items').html();
                                    items_count = parseInt(items_count);
                                    if(typeof items_count != "undefined" && items_count >= 0)
                                    {
                                        $('.orders_list_items').html((items_count+1));
                                    }
                                    else{
                                        $('.orders_list_items').html(0);
                                    }
                                }

                                $('.orders_list_btn').click();
                            }
                            else{
                                show_flash_message("warning",json_data.msg);
                            }



                        }

                    }

                });

            }

            return false;
        });

    /**
     * End add_to_orders_list
     */


    /**
     * Start load orders_list_items
     */

    $('body').on('click','.orders_list_btn',function () {

        var this_element = $(this);
        var is_clicked = this_element.attr('data-clicked');

        if(typeof is_clicked == "undefined")
        {
            this_element.attr('data-clicked','true');
            $('.orders_list_ul').append(' '+ajax_loader_img+' ');
            var object = {};
            object._token = _token;

            $.ajax({
                url: base_url2 + "/load_orders_list_items",
                data: object,
                type: 'POST',
                success: function (data) {

                    $('.orders_list_ul').find('.ajax_loader_class').remove();

                    var json_data=JSON.parse(data);
                    if(
                        typeof json_data.status != "undefined" &&
                        typeof json_data.li_items != "undefined" && typeof json_data.li_items_count != "undefined"
                    ){

                        if(json_data.status == "success" && json_data.li_items != "")
                        {

                            $('.orders_list_ul').html(json_data.li_items);
                            $('.orders_list_items').html(json_data.li_items_count);

                            if(json_data.li_items_count > 0)
                            {
                                $('.make_orders_list_post').parents().find('.orders_list_post_text').removeClass('hide_div');
                                $('.make_orders_list_post').removeAttr("disabled","disabled");
                            }
                            else{
                                $('.make_orders_list_post').parents().find('.orders_list_post_text').addClass('hide_div');
                            }

                        }
                    }

                }

            });
        }

    });

    /**
     * End load orders_list_items
     */



    /**
     * Start load remove_order_list_item
     */

    $('body').on('click','.remove_order_list_item',function () {

        var this_element = $(this);
        var post_id = this_element.attr('data-post_id');
        post_id = parseInt(post_id);

        if(typeof post_id != "undefined" && post_id > 0)
        {
            this_element.attr('disabled','disabled');
            this_element.append(' '+ajax_loader_img+' ');

            var object = {};
            object._token = _token;
            object.post_id = post_id;

            $.ajax({
                url: base_url2 + "/remove_order_list_item",
                data: object,
                type: 'POST',
                success: function (data) {

                    this_element.find('.ajax_loader_class').remove();
                    this_element.removeAttr("disabled");

                    var json_data=JSON.parse(data);
                    if(
                        typeof json_data.status != "undefined" && json_data.status == "success"
                    ){

                        if ($('.remove_order_list_item').length == 1)
                        {
                            $('.make_orders_list_post').parents().find('.orders_list_post_text').addClass('hide_div');
                        }

                        this_element.parents('li').remove();

                        var items_count = $('.orders_list_items').html();
                        items_count = parseInt(items_count);
                        if(typeof items_count != "undefined" && items_count >= 0)
                        {
                            $('.orders_list_items').html((items_count-1));
                        }
                        else{
                            $('.orders_list_items').html(0);
                        }

                    }

                }

            });
        }

    });

    /**
     * End load remove_order_list_item
     */



    /**
     * Start make_orders_list_post
     */

    $('body').on('click','.make_orders_list_post',function () {

        var this_element = $(this);
        var orders_list_post_text = this_element.parents().find('.orders_list_post_text');

        if(typeof orders_list_post_text != undefined)
        {

            this_element.attr('disabled','disabled');
            this_element.append(' '+ajax_loader_img+' ');

            var object = {};
            object._token = _token;
            object.post_body = orders_list_post_text.val();

            $.ajax({
                url: base_url2 + "/make_orders_list_post",
                data: object,
                type: 'POST',
                success: function (data) {

                    this_element.find('.ajax_loader_class').remove();

                    var json_data=JSON.parse(data);
                    if(
                        typeof json_data.status != "undefined" && json_data.status == "success" &&
                        typeof json_data.url != "undefined" && json_data.url != ""
                    ){

                        $('.orders_list_ul').find('.order_list_li_item').html("");
                        location.href = json_data.url;
                    }
                }

            });
        }

    });

    /**
     * End make_orders_list_post
     */


    /**
     * Start load_all_orders_list
     */

        $('body').on('click','.load_all_orders_list a',function () {

            var this_element = $(this);
            var items = this_element.parents('table').find('.hide_tr_element');

            if(typeof items != "undefined" && items.length)
            {

                this_element.parents('tr').remove();
                $.each(items,function (ind,val) {
                    $(this).removeClass('hide_tr_element');
                });

            }

            return false;
        });

    /**
     * End load_all_orders_list
     */


    $("#search").keyup(function (e) {
        if(e.which==13){
            $("#search_btn").click();
        }
    });

    $("#search_btn").click(function () {
        location.href=base_url2+lang_url_class+"search/"+$("#search").val();
    });

    var selected_currency_data = $('.selected_currency_data');
    if(typeof(selected_currency_data) != "undefined")
    {
        var code = selected_currency_data.attr("data-code");
        var sign = selected_currency_data.attr("data-sign");
        var rate = selected_currency_data.attr("data-rate");

        if(typeof(code) != "undefined" && typeof(sign) != "undefined" && typeof(rate) != "undefined" )
        {

            $.each($('.currency_value'),function (ind,val) {

                var origin_price = $(this).attr("data-original_price");
                if(typeof(origin_price) != "undefined")
                {

                    $(this).html((parseFloat(origin_price) * parseFloat(rate)).toFixed(2));
                    $(this).parents().find('.currency_sign').html(sign);

                }

            });
        }


    }

    $('.menu_select_currency').click(function () {

        var this_element = $(this);
        var code = this_element.attr("data-code");
        var sign = this_element.attr("data-sign");
        var rate = this_element.attr("data-rate");


        if(typeof(code) != "undefined" && typeof(sign) != "undefined" && typeof(rate) != "undefined" )
        {

            $.ajax({
                url: base_url2 + lang_url_class + "change_currency",
                data: {"_token": _token, "code": code, "sign": sign, "rate":rate},
                type: 'POST',
                success: function (data) {
                    var return_data = JSON.parse(data);
                    if (typeof (return_data.error) != "undefined" && return_data.error != "error") {
                        location.href = window.location.href;
                    } else {
                        return false;
                    }
                }

            });

        }


        return false;
    });


    /**
     * End Not Used Js until now
     */

});

