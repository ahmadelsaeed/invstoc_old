$(function(){


    $(".load_groups_workshops_btn").click(function () {

        var this_element = $(this);
        var load_groups_workshops_ul = $(".load_groups_workshops_ul");

        if(load_groups_workshops_ul.html().length==0){
            var object = {};
            object._token = _token;

            load_groups_workshops_ul.append(ajax_loader_img_func("15px"));

            $.ajax({
                url: base_url2  + "/groups_workshops/get_user_groups_and_workshops",
                data: object,
                type: 'POST',
                success: function (data) {
                    var json_data=JSON.parse(data);
                    if(typeof (json_data.html)!=undefined){
                        load_groups_workshops_ul.html(json_data.html);
                    }

                }

            });
        }
    });


    $(".group_or_workshop_class").change(function(){
        $(".workshop_selects_activity").hide();

        if($(".group_or_workshop_class:checked").val()=="Workshop"){
            $(".workshop_selects_activity").show();
        }
    });

    var add_group=function(this_element){

        var object = {};
        object.group_name = $(".group_or_workshop_name_class").val();
        object._token = _token;

        if(object.group_name.length==0){
            $(".group_or_workshop_name_class").css("border","1px solid red");
            return false;
        }

        $(".group_or_workshop_name_class").css("border","1px solid #f1f2f2");

        this_element.append(ajax_loader_img_func("15px"));
        this_element.attr("disabled","disabled");

        $.ajax({
            url: base_url2 + "/groups_workshops/create_group",
            data: object,
            type: 'POST',
            success: function (data) {
                var json_data=JSON.parse(data);

                this_element.removeAttr("disabled");
                this_element.children(".ajax_loader_class").remove();

                if(typeof (json_data.added_html)!=undefined){
                    $(".load_groups_workshops_ul").append(json_data.added_html);
                }

            }

        });
    };

    var add_workshop=function(this_element){

        var object = {};
        object.workshop_name = $(".group_or_workshop_name_class").val();
        object.workshop_cat_id = $("#workshop_child_activity_id").val();
        object._token = _token;

        if(object.workshop_name.length==0){
            $(".group_or_workshop_name_class").css("border","1px solid red");
            return false;
        }

        if(!(object.workshop_cat_id>0)){
            $(".workshop_child_activity_div_container").show();
            $("#workshop_child_activity_id").css("border","1px solid red");
            return false;
        }

        $(".group_or_workshop_name_class").css("border","1px solid #f1f2f2");
        $("#workshop_child_activity_id").css("border","1px solid #f1f2f2");

        this_element.append(ajax_loader_img_func("15px"));
        this_element.attr("disabled","disabled");

        $.ajax({
            url: base_url2 + "/groups_workshops/create_workshop",
            data: object,
            type: 'POST',
            success: function (data) {
                var json_data=JSON.parse(data);

                this_element.removeAttr("disabled");
                this_element.children(".ajax_loader_class").remove();

                if(typeof (json_data.added_html)!=undefined){
                    $(".load_groups_workshops_ul").append(json_data.added_html);
                }

            }

        });

    };


    $(".add_group_or_workshop").click(function(){

        if($(".group_or_workshop_class:checked").val()=="Workshop"){
            add_workshop($(this));
        }
        else{
            add_group($(this));
        }

        $(".group_or_workshop_name_class").val("");

        return false;
    });


});