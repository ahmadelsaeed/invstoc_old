$(function(){

    try {
        var socket = io.connect($(".socket_link").val())
    } catch (e) {
        //warn user
    }

    if (socket !== undefined) {
        console.log("ok");

        //listen for output
        socket.on("comment_output",function(data){

            var get_data=JSON.parse(data);


            if(get_data.commenter_id!=this_user_id){
                audio.play();
                get_data.return_html=get_data.return_html.replace($(".dropdown-toggle",get_data.return_html)[0],"");

                $(get_data.add_where,$('[class="'+get_data.parent_all_classes+'"]')).append(get_data.return_html);
                $(get_data.add_where,$('[class="'+get_data.parent_all_classes+'"]')).find(".post_comment_div").last().find(".dropdown-menu").remove();
                $(get_data.add_where,$('[class="'+get_data.parent_all_classes+'"]')).find(".post_comment_div").last().find(".dropdown-toggle").remove();
                $(get_data.comment_replies_count_div,$('[class="'+get_data.parent_all_classes+'"]')).html(get_data.comment_replies_count);

                $(".all_comments_section").show();
            }
        });

        $("body").on("click",".send_to_other_users",function () {
            socket.emit("comment_input", $(".emit_input_value").val());
        });

    }
});