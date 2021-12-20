jQuery(function ($) {


    /**
     * Start Profile Edit Information
     */

    if($('#cover_img_file').length)
    {
        document.getElementById("cover_img_file").onchange = function() {
            document.getElementById("cover_img_form").submit();
        };
    }

    if($('#profile_img_file').length) {

        document.getElementById("profile_img_file").onchange = function () {
            document.getElementById("profile_img_form").submit();
        };
    }

    $(".show_profile_edit_fields").click(function () {

        $(this).parents('.group_info').find('.edit_dev').css({'display':"block"});
        $(this).parents('.group_info').find('.display_dev').css({'display':"none"});

        return false;
    });

    /**
     * END Profile Edit Information
     */


    /**
     * Start Follow & Un Follow user
     */

        // follow
        $('body').on('click','.follow_user',function () {


            var this_element = $(this);
            var user_id = this_element.attr('data-follower_id');
            var target_id = this_element.attr('data-target_id');

            if(typeof(user_id) != "undefined" && user_id > 0 &&
                typeof(target_id) != "undefined" && target_id > 0
            )
            {

                this_element.attr("disabled","disabled");

                $.ajax({
                    url: base_url2 + "/follow_user",
                    data: {"_token": _token, "user_id": user_id, "target_id": target_id},
                    type: 'POST',
                    success: function (data) {
                        var return_data = JSON.parse(data);
                        if (typeof (return_data.status) != "undefined" && return_data.status != "error") {

                            this_element.removeAttr("disabled");

                            this_element.removeClass('follow_user');
                            this_element.addClass('unfollow_user');

                            this_element.find('span').text("UnFollow");

                            if($('.followers_count').length)
                            {
                                var old_count = $('.followers_count').text();
                                old_count = parseInt(old_count);
                                if(old_count >= 0)
                                {
                                    var new_count = old_count+1;
                                    $('.followers_count').text(new_count);
                                }
                            }

                        } else {

                            this_element.removeAttr("disabled");
                            alert(return_data.msg);
                        }
                    }

                });

            }
            else{
                alert("Something is Wrong !!!")
            }

            return false
        });

        //unfollow
        $('body').on('click','.unfollow_user',function () {

            var this_element = $(this);
            var user_id = this_element.attr('data-follower_id');
            var target_id = this_element.attr('data-target_id');

            if(typeof(user_id) != "undefined" && user_id > 0 &&
                typeof(target_id) != "undefined" && target_id > 0
            )
            {

                this_element.attr("disabled","disabled");

                $.ajax({
                    url: base_url2 + "/unfollow_user",
                    data: {"_token": _token, "user_id": user_id, "target_id": target_id},
                    type: 'POST',
                    success: function (data) {
                        var return_data = JSON.parse(data);
                        if (typeof (return_data.status) != "undefined" && return_data.status != "error") {

                            this_element.removeAttr("disabled");

                            this_element.removeClass('unfollow_user');
                            this_element.addClass('follow_user');

                            this_element.find('span').text("Follow");

                            if($('.followers_count').length)
                            {
                                var old_count = $('.followers_count').text();
                                old_count = parseInt(old_count);
                                if(old_count >= 0)
                                {
                                    var new_count = old_count-1;
                                    $('.followers_count').text(new_count);
                                }
                            }

                        } else {

                            this_element.removeAttr("disabled");
                            alert(return_data.msg);
                        }
                    }

                });

            }
            else{
                alert("Something is Wrong !!!")
            }

        return false
    });

    /**
     * END Follow & Un Follow user
     */


    /**
     * Start Menu Active Links
     */

    if ($('.profile_menu_links').length)
    {

        var current_link = location.href;

        $.each($('.profile_menu_links li a'),function (ind,val) {

            var this_href = $(this).attr('href');
            if (this_href == current_link)
            {
                $(this).parent().addClass('active');
            }
            else{
                $(this).parent().removeClass('active');
            }

        });

    }

    /**
     * END Menu Active Links
     */


    /**
     * Start Hover change profile & cover images
     */

    // for profile image
        $('.profile_img').mouseover(function () {

            $('.profile_img_form').removeClass("hide_element");

            return false;
        });


        $('.profile_img_div').mouseover(function () {

            $(this).parent().removeClass("hide_element");

            return false;
        });

        $('.profile_img_div').mouseleave(function () {

            $(this).parent().addClass("hide_element");

            return false;
        });

        $('.profile_img').mouseleave(function () {

            $('.profile_img_form').addClass("hide_element");

            return false;
        });

    // for cover image
        $('.cover_img').mouseover(function () {

            $('.cover_img_form').removeClass("hide_element");

            return false;
        });


        $('.cover_img_div').mouseover(function () {

            $(this).parent().removeClass("hide_element");

            return false;
        });

        $('.cover_img_div').mouseleave(function () {

            $(this).parent().addClass("hide_element");

            return false;
        });

        $('.cover_img').mouseleave(function () {

            $('.cover_img_form').addClass("hide_element");

            return false;
        });

    /**
     * End Hover change profile & cover images
     */



    /**
     * END Profile Edit Information
     */


    /**
     * Start Follow & Un Follow Workshop
     */

    // follow
    $('body').on('click','.follow_workshop',function () {


        var this_element = $(this);
        var status = this_element.attr('data-status');
        var workshop_id = this_element.attr('data-workshop_id');

        console.log(status);
        console.log(workshop_id);

        if(typeof(status) != "undefined" &&
            (status == 'unfollow' || status == 'follow') &&
            typeof(workshop_id) != "undefined" && workshop_id > 0
        )
        {

            this_element.attr("disabled","disabled");

            $.ajax({
                url: base_url2 + "/follow_workshop",
                data: {"_token": _token, "status": status, "workshop_id": workshop_id},
                type: 'POST',
                success: function (data) {
                    var return_data = JSON.parse(data);
                    if (typeof (return_data.status) != "undefined" && return_data.status != "error") {

                        this_element.removeAttr("disabled");


                        if(status == 'follow')
                        {
                            this_element.text("UnFollow");
                            this_element.attr('data-status','unfollow');
                            this_element.removeClass('btn-info');
                            this_element.addClass('btn-danger');
                            if($('.count_followers').length)
                            {
                                var old_count = $('.count_followers').text();
                                old_count = parseInt(old_count);
                                if(old_count >= 0)
                                {
                                    var new_count = old_count+1;
                                    $('.count_followers').text(new_count);
                                }
                            }

                        }
                        else{
                            this_element.text("Follow");
                            this_element.attr('data-status','follow');
                            this_element.removeClass('btn-danger');
                            this_element.addClass('btn-info');
                            if($('.count_followers').length)
                            {
                                var old_count = $('.count_followers').text();
                                old_count = parseInt(old_count);
                                if(old_count >= 0)
                                {
                                    var new_count = old_count-1;
                                    $('.count_followers').text(new_count);
                                }
                            }

                        }




                    } else {

                        this_element.removeAttr("disabled");
                        alert(return_data.msg);
                    }
                }

            });

        }
        else{
            alert("Something is Wrong !!!")
        }

        return false
    });


    /**
     * END Follow & Un Follow user
     */


    /**
     * Start Performance Charts
     */

        if ($('.orders_statistics').length)
        {

            google.charts.load("current", {packages:["corechart"]});
            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {

                var orders_statistics = $('.orders_statistics').val();
                orders_statistics = JSON.parse(orders_statistics);

                var data = google.visualization.arrayToDataTable([
                    ['Order Status', 'Count'],
                    [$('.profit_label').val(),     orders_statistics["profit"]],
                    [$('.equal_label').val(),     orders_statistics["equal"]],
                    [$('.lose_label').val(),     orders_statistics["loose"]],
                ]);

                var options = {
                    title: $('.orders_statistics_label').val(),
                    pieHole: 0.4,
                    colors: ['#16D620', '#3B3EAC', '#DC3912']
                };

                var chart = new google.visualization.PieChart(document.getElementById('orders_statistics'));
                chart.draw(data, options);
            }

        }


        if ($('.orders_posts_statistics').length)
        {

            google.charts.load("current", {packages:["corechart"]});
            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {

                var orders_posts_statistics = $('.orders_posts_statistics').val();
                orders_posts_statistics = JSON.parse(orders_posts_statistics);

                var data = google.visualization.arrayToDataTable([
                    [$('.orders_posts_statistics_label').val(), 'Count'],
                    [$('.profit_label').val(),     orders_posts_statistics["profit"]],
                    [$('.lose_label').val(),     orders_posts_statistics["lose"]],
                ]);

                var options = {
                    title: $('.orders_posts_statistics_label').val(),
                    pieHole: 0.4,
                    colors: ['#16D620', '#DC3912' ]
                };

                var chart = new google.visualization.PieChart(document.getElementById('orders_posts_statistics'));
                chart.draw(data, options);
            }

        }


        if ($('.performance_report').length)
        {

            google.charts.load('current', {'packages':['line']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {

                var performance_report = $('.performance_report').val();
                performance_report = JSON.parse(performance_report);

                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Day');
                data.addColumn('number', $('.profit_label').val());
                data.addColumn('number', $('.equal_label').val());
                data.addColumn('number', $('.lose_label').val());

                var rows_arr = [];
                // console.log(performance_report);

                $.each(performance_report,function (ind, val) {

                    rows_arr.push([
                        ind,
                        (val.profit?val.profit:0),
                        (val.equal?val.equal:0),
                        (val.lose?val.lose:0)
                    ]);

                });


                data.addRows(rows_arr);

                var options = {
                    chart: {
                        title: $('.performance_chart_header').val()
                    },
                    width: 900,
                    height: 500,
                    colors: ['#16D620', '#3B3EAC', '#DC3912']
                };

                var chart = new google.charts.Line(document.getElementById('performance_report'));

                chart.draw(data, google.charts.Line.convertOptions(options));
            }

        }

    /**
     * End Performance Charts
     */



});


