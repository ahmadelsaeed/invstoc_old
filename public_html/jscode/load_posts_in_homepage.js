$(function(){

    var fire_load_posts_works=false;

    var load_post=function(posts_ids,append_class){

        if(posts_ids[0]==undefined){
            fire_load_posts_works=false;
            return false;
        }

        var contain_slider = "";

        var object = {};
        object.post_id = posts_ids[0];
        object._token = _token;

        if(typeof ($(".show_users_post").data("postview"))!=undefined){
            object.post_view = $(".show_users_post").data("postview");
        }

        if(typeof ($(".show_users_post").data("target_search")) != undefined){
            object.target_search = $(".show_users_post").data("target_search");
        }

        $.ajax({
            url: base_url2 + "/posts/show_post",
            data: object,
            type: 'POST',
            success: function (data) {
                var json_data=JSON.parse(data);
                console.log(json_data.post_html);
                if(typeof (json_data.post_html)!="undefined"){
                    $(append_class).append(json_data.post_html);
                }
                if(typeof (json_data.contain_slider)!="undefined"){
                    contain_slider = true
                }

                $('.post_play_video').magnificPopup({
                    disableOn: 700,
                    type: 'iframe',
                    mainClass: 'mfp-fade',
                    removalDelay: 160,
                    preloader: false,
                    fixedContentPos: false
                });
            }

        }).then(function(){

            if (contain_slider != "")
            {
                jssor_slider1_starter('jssor_slider_'+object.post_id);
            }
            posts_ids.shift();
            return load_post(posts_ids,append_class);
        });

    };

    var fire_load_posts=function(append_class){

        if(fire_load_posts_works){
            return false;
        }

        fire_load_posts_works=true;

        var parent_div=$(append_class);

        var object = {};
        object._token = _token;
        object.offset = parent_div.attr("data-offset");

        if(typeof parent_div.attr("data-offset")=="undefined"){
            return false;
        }

        if(typeof parent_div.attr("data-group_id")!="undefined"){
            object.group_id = parent_div.attr("data-group_id");
        }

        if(typeof parent_div.attr("data-workshop_id")!="undefined"){
            object.workshop_id = parent_div.attr("data-workshop_id");
        }

        if(typeof parent_div.attr("data-target_search")!="undefined"){
            object.target_search = parent_div.attr("data-target_search");
        }

        var post_ids=[];

        if($('.not_closed').length)
        {
            object.not_closed = true;
        }

        $.ajax({
            url: base_url2  + "/"+$(".show_users_post").data("url"),
            data: object,
            type: 'POST',
            success: function (data) {
                var json_data=JSON.parse(data);

                if(typeof (json_data.posts_ids)!="undefined"){
                    post_ids=json_data.posts_ids;
                }

                if(typeof (json_data.post_limit)!="undefined"){
                    parent_div.attr("data-offset",parseInt(parent_div.attr("data-offset"))+parseInt(json_data.post_limit));
                }
                else{
                    parent_div.removeAttr("data-offset");
                }
            }
        }).then(function(){
            if(post_ids.length==0){
                fire_load_posts_works=false;
                return false;
            }

            load_post(post_ids,append_class);
        });


    };


    if($(".show_users_post").html()==""){
        fire_load_posts(".show_users_post");
    }

    $('body').on('click','.load-more-posts',function () {
        fire_load_posts(".show_users_post");
    });


        /*$(window).scroll(function() {
            if($(".post_div").length>0){

                var hT = $(".post_div").last().offset().top,
                    hH = $(".post_div").last().outerHeight(),
                    wH = $(window).height(),
                    wS = $(this).scrollTop();


                if (wS > (hT+hH-wH)){
                    fire_load_posts(".show_users_post");
                }
            }
        });*/


});
