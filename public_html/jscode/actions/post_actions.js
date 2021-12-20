$(function(){

    if($(".add_comment").length>0){

        $.each($(".add_comment"),function(){
            if($(this)[0].style.display!="none"){
                show_emotions($(this));
            }
        });

    }

    if($(".highlight_comment_id").length>0){
        $($(".highlight_comment_id").val()).css("background","#EEE");
        $(window).scrollTop($($(".highlight_comment_id").val()).offset().top-70);
    }

    var like_xhr;
    $("body").on("click",".like_post",function(){

        if($(this).attr("disabled")=="disabled"){
            return false;
        }

        if(like_xhr)like_xhr.abort();

        var this_element = $(this);
        var parent_element=this_element.parents(".post_div");
        this_element.attr("disabled","disabled");


        var object = {};
        object.post_id = this_element.data("postid");
        object._token = _token;


        like_xhr=$.ajax({
            url: base_url2 + "/posts/like_post",
            data: object,
            type: 'POST',
            success: function (data) {
                var json_data=JSON.parse(data);

                if(typeof (json_data.response)!="undefined"){
                    this_element.removeAttr("disabled");
                    if(json_data.response=="liked"){
                        this_element.css("fill","#00aa50");
                    }
                    else{
                        this_element.css("fill","#c2c5d9");
                    }

                    $(".post_likes_count",parent_element).html(json_data.post_likes_count);
                }

            }

        });


        return false;
    });

    $("body").on("click",".share_post",function(){
        var this_element = $(this);
        var parent_element=this_element.parents(".post_div");

        var object = {};
        object.post_id = this_element.data("postid");
        object._token = _token;

        if($(".share_modal_"+object.post_id).length>0){
            $(".share_modal_"+object.post_id).modal("show");
            return false;
        }

        this_element.html(ajax_loader_img_func("10px"));
        this_element.attr("disabled","disabled");

        $.ajax({
            url: base_url2 + "/posts/share_post",
            data: object,
            type: 'POST',
            success: function (data) {
                var json_data=JSON.parse(data);

                if(typeof (json_data.return_html)!="undefined"){
                    this_element.removeAttr("disabled");
                    this_element.html('<i class="fa fa-refresh" aria-hidden="true"></i>');

                    if(typeof (json_data.return_html)!="undefined"){

                        $(this_element).parents("body").append(json_data.return_html);
                        $(".share_modal_"+object.post_id).modal("show");
                         show_emotions($(".share_modal_"+object.post_id).find('.post_text'));

                    }
                }

            }

        });

        return false;
    });

    $("body").on("click",".load_comments_setting",function(){
        var this_element = $(this);
        var parent_element=this_element.parents(".post_div");

        var object = {};
        object.post_id = this_element.data("postid");
        object._token = _token;

        if($(".comment_section_"+object.post_id+"_0").length>0){
            $(".add_comment[data-commentid='0']",parent_element).focus();
            show_emotions($(".add_comment[data-commentid='0']",parent_element));
            return false;
        }

        this_element.attr("disabled","disabled");

        $.ajax({
            url: base_url2 + "/posts/load_post_comments",
            data: object,
            type: 'POST',
            success: function (data) {
                var json_data=JSON.parse(data);

                if(typeof (json_data.return_html)!="undefined"){
                    this_element.removeAttr("disabled");

                    $(".comments_section",parent_element).html(json_data.return_html);
                }

                show_emotions($(".add_comment[data-commentid='0']",parent_element));
            }

        });

        return false;
    });

    $("body").on("click",".comment_upload_img_btn",function(){
        var parent_element=$(this).parents(".write_comment_div").first();

        var e = jQuery.Event("keypress");
        e.which="13";
        e.shiftKey=false;
         $(".add_comment .emojionearea-editor",parent_element).trigger(e);
        $(".add_comment",parent_element).trigger(e);

        return false;
    });

    $("body").on("keypress",".add_comment .emojionearea-editor",function(e){
    //$("body").on("keypress",".add_comment",function(e){

        if(e.which=="13"&&e.shiftKey==false){
            $("img",$(this)).removeAttr("alt");


            var this_element = $(this);
            var parent_element=this_element.parents(".comments_section");
            var comment_img=$(".comment_upload_img_input",$(this).parents(".write_comment_div"))[0];
            var comment_id=0;
            var post_id=this_element.data("postid");
            var current_comment_val = $(this).html();
            //var current_comment_val = $(this).val();

            //add  a unique class to parent element
            var parent_all_classes=parent_element.attr("class");

            if($.trim(current_comment_val).length==0&&comment_img.files.length==0){
                return false;
            }

            /*if($.trim($(".edit_comment_text",parent_element).html()).length==0&&comment_img.files.length==0){
            return false;
        }*/

            if(this_element.data("commentid")!=undefined){
                comment_id=this_element.data("commentid");
            }

            var form_data=new FormData();

            if(comment_img.files && comment_img.files.length>0){
                form_data.append("comment_file",comment_img.files[0]);
            }

            form_data.append("post_id",this_element.data("postid"));
            form_data.append("comment_id",comment_id);
            form_data.append("comment_body",current_comment_val);
            form_data.append("_token",_token);

            this_element.attr("disabled","disabled");

            $.ajax({
                url: base_url2 + "/posts/add_comment",
                type: 'POST',
                data: form_data,
                cache: false,
                processData: false,
                contentType: false,
                success: function (data) {
                    var json_data=JSON.parse(data);
                    var emit_input_value={};

                    emit_input_value.parent_all_classes=parent_all_classes;

                    if(typeof (json_data.return_html)!="undefined"){
                        this_element.removeAttr("disabled");
                        $(".comment_section_"+post_id+"_"+comment_id,parent_element).append(json_data.return_html);
                        $(".all_comments_section",parent_element).show();
                        this_element.val("");

                        emit_input_value.commenter_id=json_data.commenter_id;
                        emit_input_value.add_where=".comment_section_"+post_id+"_"+comment_id;
                        emit_input_value.return_html=json_data.return_html;
                    }

                    if(typeof (json_data.comment_replies_count)!="undefined"){
                        $(".replies_count",this_element.parents(".parent_comment")).html(json_data.comment_replies_count);

                        emit_input_value.comment_replies_count_div=".replies_count";
                        emit_input_value.comment_replies_count=json_data.comment_replies_count;
                    }

                    if(typeof (json_data.post_comments_count)!="undefined") {
                        $(".post_comments_count",this_element.parents(".post_div")).html(json_data.post_comments_count);

                        emit_input_value.post_comments_count=json_data.post_comments_count;
                    }

                    $(".comment_upload_img_input",this_element.parents(".write_comment_div")).val("");

                    $(".emit_input_value").val(JSON.stringify(emit_input_value));

                    $(".send_to_other_users").click();

                    this_element.html("");
                   // this_element.val("");
                }

            });

            return false;
        }
    });

    var like_comment_xhr;
    $("body").on("click",".like_comment",function(){

        if($(this).attr("disabled")=="disabled"){
            return false;
        }

        if(like_comment_xhr)like_comment_xhr.abort();

        var this_element = $(this);
        this_element.attr("disabled","disabled");

        var object = {};
        object.comment_id = this_element.data("commentid");
        object._token = _token;


        like_comment_xhr=$.ajax({
            url: base_url2 + "/posts/like_comment",
            data: object,
            type: 'POST',
            success: function (data) {
                var json_data=JSON.parse(data);

                if(typeof (json_data.response)!="undefined"){
                    this_element.removeAttr("disabled");

                    if(json_data.response=="liked"){
                        $(".like_text",this_element).addClass("is_liked_post");
                    }
                    else{
                        $(".like_text",this_element).removeClass("is_liked_post");
                    }

                    $(".like_count",this_element).html(json_data.comment_likes_count);
                }

            }

        });


        return false;
    });

    $("body").on("click",".edit_comment",function(){

        var this_element=$(this);

        var object = {};
        object._token = _token;
        object.comment_id = this_element.data("commentid");

        if($(".edit_comment_modal_"+object.comment_id).length>0){
            $(".edit_comment_modal_"+object.comment_id).modal("show");
            return false;
        }

        this_element.append(ajax_loader_img_func("10px"));
        this_element.attr("disabled","disabled");

        $.ajax({
            url: base_url2 + "/posts/edit_comment",
            data: object,
            type: 'POST',
            success: function (data) {
                this_element.removeAttr("disabled");
                this_element.children(".ajax_loader_class").remove();
                var json_data=JSON.parse(data);

                if(typeof (json_data.return_html)!="undefined"){
                    $(this_element).parents("body").append(json_data.return_html);
                    $(".edit_comment_modal_"+object.comment_id).modal("show");


                    show_emotions($(".edit_comment_modal_"+object.comment_id+" .edit_comment_text"));
                    console.log($(".edit_comment_modal_"+object.comment_id+" .edit_comment_text").html());
                    $(".edit_comment_modal_"+object.comment_id).find('.emojionearea-editor').html($(".edit_comment_modal_"+object.comment_id+" .edit_comment_text").val());

                }
            }

        });


        return false;
    });

    $("body").on("click",".post_edit_comment",function(){
        var parent_element = $(this).parents(".modal");
        var comment_img=$(".comment_upload_img_input",parent_element)[0];

        // if($.trim($(".edit_comment_text .emojionearea-editor",parent_element).html()).length==0&&comment_img.files.length==0){
        //     return false;
        // }

        if($.trim($(".edit_comment_text",parent_element).html()).length==0&&comment_img.files.length==0){
            return false;
        }

        var this_element = $(this);
        var comment_id=this_element.data("commentid");

        var form_data=new FormData();
        form_data.append("comment_id",comment_id);
        form_data.append("comment_body",$(".edit_comment_text .emojionearea-editor",parent_element).html());
        //form_data.append("comment_body",$(".edit_comment_text",parent_element).val());
        form_data.append("_token",_token);

        if($(".keep_image",parent_element).length){
            form_data.append("keep_image","keep_image");
        }

        if(comment_img.files.length>0){
            form_data.append("comment_file",comment_img.files[0]);
        }

        this_element.attr("disabled","disabled");

        $.ajax({
            url: base_url2 + "/posts/post_edit_comment",
            data: form_data,
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            success: function (data) {
                var json_data=JSON.parse(data);

                if(typeof (json_data.return_html)!="undefined"){
                    this_element.removeAttr("disabled");
                    $(".comment_body_"+comment_id).html(json_data.return_html);

                    $(".edit_comment_modal_"+comment_id).modal("hide");
                    $(".edit_comment_modal_"+comment_id).remove();
                    $(".modal-backdrop").remove();
                    $("body").removeClass("modal-open");
                }

            }

        });

    });

    $("body").on("click",".make_comment",function(){
        var this_element=$(this);
        var parent_element=this_element.parents(".post-comment");

        $(".write_comment_block",parent_element).show();
        if($(".add_comment",parent_element).length&&$(".add_comment",parent_element)[0].style.display!="none"){
            show_emotions($(".add_comment",parent_element));
        }

        return false;
    });

    $("body").on("click",".load_comments",function(){

        var this_element = $(this);
        var comments_section=this_element.parents(".comments_section").first();

        var load_parent_comments_or_child="parent";
        if(this_element.data("commentid")!="0"){
            load_parent_comments_or_child="child";
        }


        var object = {};
        object.comment_id = this_element.data("commentid");
        object.post_id = this_element.data("postid");
        object.get_next_or_prev = this_element.data("get_next_or_prev");
        object._token = _token;

        if(object.get_next_or_prev=="next"){
            if(load_parent_comments_or_child=="parent"){
                object.last_comment_loaded=this_element.parents(".post-comment").prev().attr("data-commentid");
            }
            else{
                object.last_comment_loaded=$(".post_comment_div",comments_section).last().data("commentid");
            }

        }
        else{
            object.after_comment=this_element.parent().next().data("commentid");
            object.before_comment=this_element.parent().prev().prev().data("commentid");

            if(object.after_comment==undefined){
                object.after_comment=0;
            }

            if(object.before_comment==undefined){
                object.before_comment=0;
            }
        }

        if(object.last_comment_loaded==undefined){
            object.last_comment_loaded=0;
        }

        console.log("object",object);

        this_element.append(ajax_loader_img_func("15px"));
        this_element.attr("disabled","disabled");

        $.ajax({
            url: base_url2 + "/posts/load_more_comments",
            data: object,
            type: 'POST',
            success: function (data) {
                var json_data=JSON.parse(data);
                this_element.removeAttr("disabled");
                this_element.children(".ajax_loader_class").remove();

                if(typeof (json_data.return_html)!="undefined"){
                    this_element.parent().before(json_data.return_html);
                }

                if(typeof (json_data.remove_load_more)!="undefined"){
                    this_element.parent().remove();
                }
            }

        });

        return false;
    });


    /**
     * Start Get Full Users likes
     */

        $('body').on('click','.get_post_username_likes',function () {

            var this_element = $(this);
            var post_id = this_element.attr('data-post_id');
            post_id = parseInt(post_id);

            if(typeof post_id != "undefined" && post_id > 0)
            {

                $('.users_modal_content').find('.modal-body').html(modal_ajax_loader_img);
                $('.users_modal_content').modal('show');

                var object = {};
                object._token = _token;
                object.post_id = post_id;

                $.ajax({
                    url: base_url2 + "/posts/get_post_username_likes",
                    data: object,
                    type: 'POST',
                    success: function (data) {
                        var json_data=JSON.parse(data);

                        if(typeof (json_data.html)!="undefined" && json_data.html != ""){
                            $('.users_modal_content').find('.modal-body').html(json_data.html);
                        }
                        else{
                            $('.users_modal_content').find('.modal-body').html("");
                        }
                    }

                });

            }

            return false;
        });

    /**
     * End Get Full Users likes
     */

    /**
     * Start Get Full Users comments
     */

        $('body').on('click','.get_post_username_comments',function () {

            var this_element = $(this);
            var post_id = this_element.attr('data-post_id');
            post_id = parseInt(post_id);

            if(typeof post_id != "undefined" && post_id > 0)
            {

                $('.users_modal_content').find('.modal-body').html(modal_ajax_loader_img);
                $('.users_modal_content').modal('show');

                var object = {};
                object._token = _token;
                object.post_id = post_id;

                $.ajax({
                    url: base_url2 + "/posts/get_post_username_comments",
                    data: object,
                    type: 'POST',
                    success: function (data) {
                        var json_data=JSON.parse(data);

                        if(typeof (json_data.html)!="undefined" && json_data.html != ""){
                            $('.users_modal_content').find('.modal-body').html(json_data.html);
                        }
                        else{
                            $('.users_modal_content').find('.modal-body').html("");
                        }
                    }

                });

            }

            return false;
        });

    /**
     * End Get Full Users comments
     */

    /**
     * Start Get Full Users shares
     */

        $('body').on('click','.get_post_username_shares',function () {

            var this_element = $(this);
            var post_id = this_element.attr('data-post_id');
            post_id = parseInt(post_id);

            if(typeof post_id != "undefined" && post_id > 0)
            {

                $('.users_modal_content').find('.modal-body').html(modal_ajax_loader_img);
                $('.users_modal_content').modal('show');

                var object = {};
                object._token = _token;
                object.post_id = post_id;

                $.ajax({
                    url: base_url2 + "/posts/get_post_username_shares",
                    data: object,
                    type: 'POST',
                    success: function (data) {
                        var json_data=JSON.parse(data);

                        if(typeof (json_data.html)!="undefined" && json_data.html != ""){
                            $('.users_modal_content').find('.modal-body').html(json_data.html);
                        }
                        else{
                            $('.users_modal_content').find('.modal-body').html("");
                        }
                    }

                });

            }

            return false;
        });

    /**
     * End Get Full Users shares
     */

});