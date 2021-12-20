var imgs_input;

$(function () {

    show_emotions($(".post_text"));

    var deleted_files_indices=[];
    var posts_youtube_links={};

    var upload_file=function(file_index){

        if(deleted_files_indices.indexOf(file_index)>=0){
            return false;
        }

        var form_data=new FormData();
        form_data.append("file",imgs_input.files[file_index]);
        form_data.append("_token",_token);

        $.ajax({
            url: base_url2 + "/test_ajax",
            type: 'POST',
            data: form_data,
            cache: false,
            processData: false,
            contentType: false,
            success: function (data) {


                $(".preview_post_img_div").get(file_index).style="box-shadow:3px 0px 13px green";

            },
            async: false
        });

    };

    $("body").on("change",".post_imgs",function(){

        imgs_input=$(this)[0];
        var append_where=$(this).data("appendwhere");

        if(imgs_input.files.length==0){
            $(append_where).html("");
            return false;
        }

        $(append_where).html("");


        var readers=[];
        deleted_files_indices=[];

        $.each(imgs_input.files,function(k,val){
            readers[k] = new FileReader();

            readers[k].onload = function (e) {
                $(append_where).append(
                    "<div class='preview_post_img_div'>" +
                        "<img src='"+e.target.result+"' class='preview_post_img' />" +
                        "<div class='remove_img'>" +
                        "<a href='#' class='remove_img_from_arr'><i class='fa fa-times'></i></a>" +
                        "</div>"+
                    "</div>"
                );
            }

            readers[k].readAsDataURL(val);
        });


        // $.each(imgs_input.files,function(k,val) {
        //     upload_file(k);
        // });

    });


    $("body").on("click",".remove_img_from_arr",function(){
        var preview_post_img_div=$(this).parents(".preview_post_img_div");

        deleted_files_indices.push(preview_post_img_div.index());
        preview_post_img_div.remove();


        return false;
    });


    $("body").on("keyup",".post_text",function(event){

        if((event.which=="86"&&event.ctrlKey==true)||event.which=="8"||event.which=="46"){

            var parent_add_post_div=$(this).parents(".parent_add_post_div");

            posts_youtube_links={};

           // var links=extract_links_fun($(".emojionearea-editor",parent_add_post_div).html().replace(/<[^>]+>/g, ' '),"youtu.be");
             var links=extract_links_fun($(".emojionearea-editor",parent_add_post_div).html().replace(/<[^>]+>/g, ' '),"youtube.com");
            //var links=extract_links_fun($(this).val().replace(/<[^>]+>/g, ' '),"youtube.com");
            links=Array.from(new Set(links));
            console.log(links);
            var youtube_links="";

            $.each(links,function(i,link){

                var link_data=getUrlVars(link);
                if(link_data.v=="undefined"){
                    return false;
                }
                //console.log("https://www.googleapis.com/youtube/v3/videos?id="+link_data.v+"&key=AIzaSyCMkhi7T1z-gzewHrUKh1caXoExvf1W7TA&part=snippet,contentDetails,statistics,status");
                // console.log("https://www.googleapis.com/youtube/v3/videos?part=snippet%2CcontentDetails%2Cstatistics%2Cstatus&id=2sdS6CX3jfk&key=mimetic-pursuit-248913");
                var video_data;
                $.get( "https://www.googleapis.com/youtube/v3/videos?id="+link_data.v+"&key=AIzaSyCMkhi7T1z-gzewHrUKh1caXoExvf1W7TA&part=snippet,contentDetails,statistics,status", function( data ) {

                    if(typeof (data.items)!="undefined"&&typeof (data.items[0])!="undefined"&&typeof(data.items[0].snippet)!="undefined"){
                        video_data=data.items[0].snippet;
                    }
                    else{
                        video_data="undefined";
                    }
                }).then(function(){

                    if(video_data=="undefined"){
                        return false;
                    }

                    var title=video_data.localized.title;
                    var description=video_data.localized.description;
                    var img=video_data.thumbnails.default.url;

                    posts_youtube_links[link_data.v]={
                        "title":title,
                        "description":description,
                        "img":img,
                        "link":link
                    };

                    youtube_links+='<div class="post-video youtube_div">';
                        youtube_links+='<div class="youtube_inner_div">';
                            youtube_links+='<div class="video-thumb">';
                                youtube_links+='<a class="img_link" href="'+link+'" target="_blank">';
                                    youtube_links+='<img src="'+img+'" title="'+title+'" alt="'+title+'" class="youtube_img" style="height: 110px;width: 110px;">';
                                youtube_links+='</a>';
                            youtube_links+='</div>';

                            youtube_links+='<div class="video-content">';
                                youtube_links+='<a href="'+link+'" target="_blank" class="youtube_link h4 title">'+title+'</a>';

                                youtube_links+='<p class="youtube_desc"><a href="'+link+'" target="_blank" class="youtube_desc_link">'+description+'</a></p>';
                            youtube_links+='</div>';

                        youtube_links+='</div>';
                    youtube_links+='</div>';

                }).then(function(){
                    if(links.length>0&&links.length-1==i){
                        $(".youtube_parent_divs",parent_add_post_div).html(youtube_links);
                    }
                });
            });

        }

    });


    $("body").on("click",".add_post_btn",function(){

        var this_element=$(this);
        var parent_add_post_div=$(this).parents(".parent_add_post_div");
        imgs_input=$(".post_imgs",parent_add_post_div)[0];

        if($(".post_text",parent_add_post_div).val().length==0&&(typeof (imgs_input)=="undefined"||imgs_input.files.length==0)){
            $(".post_text",parent_add_post_div).append("add some data please");
            return false;
        }

        if($(".post_or_recommendation_class:checked",parent_add_post_div).val()=="recommendation"){
            if(!$('.order_expected_price')[0].reportValidity()){
                return false;
            }

            if(!$('.order_days_number')[0].reportValidity()){
                return false;
            }
        }




        $(".remove_img_from_arr").hide();

        this_element.append(ajax_loader_img_func("10px"));
        this_element.attr("disabled","disabled");

        var form_data=new FormData();
        form_data.append("_token",_token);
        form_data.append("share_post",this_element.data("sharepost"));
        form_data.append("post_id",this_element.data("postid"));
       // form_data.append("post_text",$(".post_text",parent_add_post_div).val());

        $("img",$(".emojionearea-editor",parent_add_post_div)).removeAttr("alt");
        let postText = $(".emojionearea-editor",parent_add_post_div).html();
        form_data.append("post_text",postText);
        // form_data.append("post_or_recommendation",$(".post_or_recommendation_class:checked",parent_add_post_div).val());
        form_data.append("post_or_recommendation",$(".post_or_recommendation_class",parent_add_post_div).val());

        form_data.append("pair_currency_id",$("#pair_currency_id_id",parent_add_post_div).val());
        form_data.append("sell_or_buy",$(".sell_or_buy_class",parent_add_post_div).val());
        form_data.append("cat_id",$("#cat_id_id",parent_add_post_div).val());
        form_data.append("posts_youtube_links",JSON.stringify(posts_youtube_links));

        form_data.append("order_expected_price",$(".order_expected_price",parent_add_post_div).val());
        form_data.append("order_stop_loss",$(".order_stop_loss",parent_add_post_div).val());
        form_data.append("order_take_profit",$(".order_take_profit",parent_add_post_div).val());


        if($(".post_where").length>0){
            form_data.append("post_where",$(".post_where").data("post_where"));
            form_data.append("post_where_id",$(".post_where").data("post_where_id"));
        }

        if($(".old_post_imgs",parent_add_post_div).length>0){
            var old_post_imgs=[];
            $.each($(".old_post_imgs",parent_add_post_div),function(i,v){
                old_post_imgs.push($(this).val());
            });
            form_data.append("old_post_imgs",JSON.stringify(old_post_imgs));
        }


        if(typeof (imgs_input)!="undefined"){
            $.each(imgs_input.files,function(file_index,val) {
                if(deleted_files_indices.indexOf(file_index)>=0){
                    return true;
                }

                form_data.append("post_files[]",imgs_input.files[file_index]);
            });
        }

        $.ajax({
            url: base_url2 + "/posts/add_post",
            type: 'POST',
            data: form_data,
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                if(myXhr.upload){
                    myXhr.upload.addEventListener('progress',function progress(e){

                        if(e.lengthComputable){
                            var max = e.total;
                            var current = e.loaded;

                            var Percentage = (current * 100)/max;

                            $(".post_progress_bar").show();
                            $(".post_progress_bar .progress-bar").css("width",parseInt(Percentage)+"%");
                        }
                    }, false);
                }
                return myXhr;
            },
            cache: false,
            processData: false,
            contentType: false,
            success: function (data) {
                console.log(data);
                data=JSON.parse(data);

                this_element.children(".ajax_loader_class").remove();
                this_element.removeAttr("disabled");

                $(".preview_post_img_div").css("box-shadow","3px 0px 13px green");

                $(".post_progress_bar").hide();


                //clear add post section
                $(".post_text").val("");
                $(".emojionearea-editor",parent_add_post_div).html("");
                $(".post_imgs").val("");
                $(".preview_imgs").html("");
                $(".youtube_parent_divs").html("");
                $(".post_or_recommendation_class").first().click();

                if(typeof (data.post_html)!="undefined"){
                    if($(".show_post_div_"+data.post_id).length>0){
                        $(".show_post_div_"+data.post_id).replaceWith(data.post_html);
                    }
                    else{
                        $(".new_posts_created").prepend(data.post_html);
                    }
                }

                if($(".edit_modal_"+this_element.data("postid")).length){
                    $(".edit_modal_"+this_element.data("postid")).modal("hide");
                    $(".edit_modal_"+this_element.data("postid")).remove();
                    $(".modal-backdrop").remove();
                    $("body").removeClass("modal-open");
                }

                if($(".share_modal_"+this_element.data("postid")).length){
                    $(".share_modal_"+this_element.data("postid")).modal("hide");
                    $(".share_modal_"+this_element.data("postid")).remove();
                    $(".modal-backdrop").remove();
                    $("body").removeClass("modal-open");
                }

                if (data.contain_slider != "undefined" && data.contain_slider == true)
                {
                    jssor_slider1_starter('jssor_slider_'+data.post_id);
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
        });


    });


    $(".post_or_recommendation_class").change(function(){

        if($(this).val()=="recommendation"){
           $(".recommendation_inputs").show();
        }
        else{
            $(".recommendation_inputs").hide();
        }
    });



});

















