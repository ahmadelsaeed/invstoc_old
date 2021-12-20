$(function(){

    $("body").on("click",".embed_youtube_video",function(){
        $(this).html('<iframe width="100%" height="315" src="'+$(this).data("embedlink")+'" frameborder="0" allowfullscreen></iframe>');

        return false;
    });

    $("body").on("click",".embed_youtube_video a",function(){
        $(this).parents(".embed_youtube_video").click();

        return false;
    });

    $("body").on("click",".make_order_not_closed",function(){

        var confirm_delete=confirm("are you sure?");
        if(!confirm_delete){
            return false;
        }

        var this_element=$(this);

        var object = {};
        object._token = _token;
        object.post_id = this_element.data("postid");

        this_element.append(ajax_loader_img_func("10px"));
        this_element.attr("disabled","disabled");

        $.ajax({
            url: base_url2 + "/posts/make_order_not_closed",
            data: object,
            type: 'POST',
            success: function (data) {

                this_element.html(data);
            }
        });


        return false;
    });


    $("body").on("click",".delete_post",function(){

        var confirm_delete=confirm("are you sure?");
        if(!confirm_delete){
            return false;
        }

        var this_element=$(this);

        var object = {};
        object._token = _token;
        object.post_id = this_element.data("postid");

        this_element.append(ajax_loader_img_func("10px"));
        this_element.attr("disabled","disabled");

        $.ajax({
            url: base_url2 + "/posts/delete_post",
            data: object,
            type: 'POST',
            success: function (data) {
                var json_data=JSON.parse(data);

                if(typeof (json_data.deleted)!="undefined"){
                    $(this_element).parents(".post_div").remove();
                }
            }

        });


        return false;
    });

    $("body").on("click",".edit_post",function(){

        var this_element=$(this);

        var object = {};
        object._token = _token;
        object.post_id = this_element.data("postid");

        if($(".edit_modal_"+object.post_id).length>0){
            $(".edit_modal_"+object.post_id).modal("show");
            return false;
        }

        this_element.append(ajax_loader_img_func("10px"));
        this_element.attr("disabled","disabled");

        $.ajax({
            url: base_url2 + "/posts/edit_post",
            data: object,
            type: 'POST',
            success: function (data) {
                this_element.removeAttr("disabled");
                this_element.children(".ajax_loader_class").remove();
                var json_data=JSON.parse(data);

                if(typeof (json_data.return_html)!="undefined"){

                    $(this_element).parents("body").append(json_data.return_html);
                    $(".edit_modal_"+object.post_id).modal("show");
                     show_emotions($(".post_text",$(".edit_modal_"+object.post_id)));

                    var get_html_post_body = $(".edit_modal_"+object.post_id).find('.post_text').val();
                     $(".edit_modal_"+object.post_id).find('.emojionearea-editor').html(get_html_post_body);
                    //$(".edit_modal_"+object.post_id).find('.post_text').val(get_html_post_body);

                }
            }

        });


        return false;
    });

    $("body").on("click",".save_post",function(){


        var this_element=$(this);

        var object = {};
        object._token = _token;
        object.post_id = this_element.data("postid");

        this_element.append(ajax_loader_img_func("10px"));
        this_element.attr("disabled","disabled");

        $.ajax({
            url: base_url2 + "/posts/save_post",
            data: object,
            type: 'POST',
            success: function (data) {
                this_element.removeAttr("disabled");
                this_element.children(".ajax_loader_class").remove();
                var json_data=JSON.parse(data);

                if(typeof (json_data.msg)!="undefined"){
                    $(this_element).html(json_data.msg);
                }
            }

        });

        return false;
    });

    $("body").on("click",".delete_comment",function(){

        var confirm_delete=confirm("are you sure?");
        if(!confirm_delete){
            return false;
        }

        var this_element=$(this);

        var object = {};
        object._token = _token;
        object.comment_id = this_element.data("commentid");

        this_element.append(ajax_loader_img_func("10px"));
        this_element.attr("disabled","disabled");

        $.ajax({
            url: base_url2 + "/posts/delete_comment",
            data: object,
            type: 'POST',
            success: function (data) {
                var json_data=JSON.parse(data);

                if(typeof (json_data.post_comments_count)!="undefined") {
                    $(".post_comments_count",this_element.parents(".post_div")).html(json_data.post_comments_count);

                    if(json_data.post_comments_count=="0"){
                        $(".all_comments_section",this_element.parents(".post_div")).hide();
                    }
                }

                if(typeof (json_data.deleted)!="undefined"){
                    this_element.parents(".write_comment_div").first().remove();
                }

            }

        });


        return false;
    });

    $("body").on("click",".add_order_closed_price",function(){

        var this_element=$(this);

        var object = {};
        object._token = _token;
        object.post_id = this_element.data("postid");

        if($(".add_order_closed_price_modal_"+object.post_id).length>0){
            $(".add_order_closed_price_modal_"+object.post_id).modal("show");
            return false;
        }

        this_element.append(ajax_loader_img_func("10px"));
        this_element.attr("disabled","disabled");

        $.ajax({
            url: base_url2 + "/posts/get_order_closed_price_modal",
            data: object,
            type: 'POST',
            success: function (data) {
                this_element.removeAttr("disabled");
                this_element.children(".ajax_loader_class").remove();
                var json_data=JSON.parse(data);

                if(typeof (json_data.return_html)!="undefined"){

                    $(this_element).parents("body").append(json_data.return_html);
                    $(".add_order_closed_price_modal_"+object.post_id).modal("show");
                }
            }

        });


        return false;
    });

    $("body").on("click",".add_order_closed_price_btn",function(){

        var this_element=$(this);
        var parent_element=this_element.parents(".add_order_closed_price_form");

        if(!$('.closed_price')[0].reportValidity()){
            return false;
        }

        var post_id = this_element.data("postid");

        var object = {};
        object._token = _token;
        object.post_id = post_id;
        object.closed_price = $(".closed_price",parent_element).val();


        this_element.append(ajax_loader_img_func("10px"));
        this_element.attr("disabled","disabled");

        $.ajax({
            url: base_url2 + "/posts/add_order_closed_price",
            data: object,
            type: 'POST',
            success: function (data) {

                var json_data = JSON.parse(data);

                this_element.removeAttr("disabled");
                this_element.children(".ajax_loader_class").remove();

                $(".add_order_closed_price_modal_"+object.post_id).modal("hide");
                $(".add_order_closed_price_modal_"+object.post_id).remove();
                $(".modal-backdrop").remove();
                $("body").removeClass("modal-open");

                $('.show_post_div_'+post_id).remove();
                $('.show_users_post').prepend(json_data.post_html);

            }

        });


        return false;
    });


    $("body").on('click','.open_post_image',function () {

        var this_element = $(this);
        var img_src = this_element.attr("src");

        if(typeof img_src != "undefined" && img_src != "")
        {

            var img_element = '<img src="'+img_src+'" width="100%" height="auto" >';

            $('.maximize_image_modal').modal('show');
            $('.maximize_image_modal').find('.modal-body').html(img_element);

        }

        return false;
    });


});