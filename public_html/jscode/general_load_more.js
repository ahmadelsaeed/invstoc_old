$(function(){

    var ajax_loader_img_func=function(img_width){
        return "<img src='" + base_url + "img/ajax-loader.gif' class='ajax_loader_class' style='width:"+img_width+";height:"+img_width+";'>";
    };

    var base_url2 = $(".url_class").val()+"/";
    var lang_url_class = $(".lang_url_class").val();
    var base_url = base_url2 + "/public_html/";
    var _token = $(".csrf_input_class").val();

    $("body").on("click",".load_more_btn",function(){

        var this_element=$(this);
        this_element.append(ajax_loader_img_func("10px"));

        var ids=$(this).attr("data-ids");
        ids=JSON.parse(ids);

        var showitems=$(this).attr("data-showitems");


        //get wanted ids
        var wanted_arr=[];
        for(i=0;i<showitems;i++){
            wanted_arr[i]=ids[0];
            ids.splice(0,1);
        }

        //update ids in this element

        this_element.attr("data-ids",JSON.stringify(ids));

        //get data
        var appendselector=$(this).attr("data-appendselector");
        var post_url=$(this).attr("data-url");

        var send_data={};
        send_data._token=_token;
        send_data.items_ids=wanted_arr;

        $.ajax({
            url:post_url,
            type:"POST",
            data:send_data,
            success:function(data){

                this_element.children("img").remove();

                if(ids.length==0){
                    this_element.remove();
                }

                data=JSON.parse(data);
                if(typeof (data.data)!="undefined"){
                    if(data.data!="no_data"){
                        console.log(data.data);
                        $(appendselector).append(data.data);
                    }
                    else{
                        this_element.remove();
                    }

                }

            }
        });


    });

});