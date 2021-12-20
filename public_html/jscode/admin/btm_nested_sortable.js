//var new_menu={};

function revoke_sortable_code(){

    $( ".btm_sortable_ul" ).sortable({
        placeholder: "ui-state-highlight",
        connectWith: "ul",
        revert: true
    });


    $( ".btm_sortable_ul" ).disableSelection();

}

function re_assign_level_values(li_items,level_value){
    //update level-value in all the tree

    $.each(li_items,function(index,value){

        var this_element=$(this);
        var classes=this_element.attr("class");
        if (classes.indexOf("btm_level")>-1) {

            //update all level-values
            this_element.attr("data-levelvalue",level_value);

            //call recursion function
            var this_li_childs=this_element.children("ul").children("li");
            if (this_li_childs.length>0) {
                var new_level_value=level_value+1;
                re_assign_level_values(this_li_childs,new_level_value);
            }

        }

    });


}//end function



function save_new_menu(li_items){
    var same_li_level_value=[];
    var signle_elemnt;
    var return_what="single";

    $.each(li_items,function(index,value){
        signle_elemnt={};

        var this_element=$(this);
        var classes=this_element.attr("class");
        if (classes.indexOf("btm_level")>-1) {

            signle_elemnt.level_data={};
            signle_elemnt.level_data.item_name=this_element.find(".collapse_div #link_text_id").val();
            signle_elemnt.level_data.item_slug=this_element.find(".collapse_div #link_href_id").val();
            signle_elemnt.level_data.item_class=this_element.find(".collapse_div #link_class_id").val();


            //call recursion function
            var this_li_childs=this_element.children("ul").children("li");
//            if (this_li_childs.length>1) {
//                //signle_elemnt.level_childs;
//                signle_elemnt.level_childs.push(save_new_menu(this_li_childs));
//            }
//            else 
            if(this_li_childs.length>0){
                console.log("enter the sec");
                signle_elemnt.level_childs=save_new_menu(this_li_childs);
            }


            same_li_level_value.push(signle_elemnt);

            if (li_items.length==same_li_level_value.length) {
                return_what="all";
            }

        }



    });
    if (return_what=="single") {
        return signle_elemnt;
    }else{
        console.log("same_li_level_value");
        console.log(same_li_level_value);
        return same_li_level_value;
    }

}



$(function(){

    var base_url2 = $(".url_class").val()+"/";
    var base_url = base_url2 + "/public_html/";

    var _token = $(".csrf_input_class").val();


    revoke_sortable_code();

    $("body").on("click",".level_in_btn",function(){

        var this_element=$(this).parents(".btm_level").first();
        console.log(this_element);

        var this_element_html=this_element.html();
        var this_element_level_value=parseInt(this_element.attr("data-levelvalue"));

        //increase level-value
        this_element_level_value=this_element_level_value+1;
        //
        //parents(".btm_level").first().parent().is("ul")


        var previous_element=this_element.prev();


        if(previous_element.is("li")==false){
            previous_element=this_element.parent().prev().children("li");
        }

        var previous_element_level_value=parseInt(previous_element.attr("data-levelvalue"));

        if ((this_element_level_value-previous_element_level_value)==1) {

            if (previous_element.children("ul").length>0) {
                previous_element.children("ul").append("<li class='btm_level' data-levelvalue='"+this_element_level_value+"'>"+this_element_html+"</li>");
            }
            else{
                previous_element.append("<ul class='btm_sortable_ul'><li class='btm_level' data-levelvalue='"+this_element_level_value+"'>"+this_element_html+"</li></ul>");
            }

            if(this_element.parent().is("ul")&&this_element.parent().children("li").length==1){
                this_element.parent().remove();
            }
            this_element.remove();


            revoke_sortable_code();
        }


        var parent_lis=$(".btm_sortable_ul").first().children("li");
        re_assign_level_values(parent_lis,0);
        return false;
    });

    $("body").on("click",".level_out_btn",function(){
        var this_element=$(this).parents(".btm_level").first();


        var this_element_html=this_element.html();
        var this_element_level_value=parseInt(this_element.attr("data-levelvalue"));


        //it will return parent
        //get parent of $(this).parent();h
        if(!(this_element_level_value>0)){
            return;
        }
        //decrease level value
        this_element_level_value=this_element_level_value-1;

        var grand_parent_element=this_element.parents('.btm_sortable_ul').parents('.btm_sortable_ul');
        console.log(grand_parent_element);

        var grand_parent_level_value=parseInt(grand_parent_element.attr("data-levelvalue"));

        var ul_element=$(grand_parent_element,"ul").first();
//        var ul_element=grand_parent_element;

        if (ul_element.is("li")) {
            ul_element.append("<ul class='btm_sortable_ul'><li class='btm_level'data-levelvalue='"+this_element_level_value+"'>"+this_element_html+"</li></ul>");
        }
        else{
            ul_element.append("<li class='btm_level'data-levelvalue='"+this_element_level_value+"'>"+this_element_html+"</li>");
        }

        if(this_element.parent().is("ul")&&this_element.parent().children("li").length==1){
            this_element.parent().remove();
        }

        this_element.remove();
        revoke_sortable_code();


        var parent_lis=$(".btm_sortable_ul").first().children("li");
        re_assign_level_values(parent_lis,0);
        return false;

    });


    $("body").on("click",".remove_menu_link",function(){

        var confirm_res = confirm("Are you Sure?");
        if (confirm_res ==true) {
            $(this).parent().parent().parent().parent().parent().remove();
        }

        return false;
    });

    $("body").on("click",".collapse_open_btn",function(){

        var parent_li=$(this).parents('.inner_li');
        var collpase_div=$(".collapse_div",parent_li);


        collpase_div.collapse("toggle");

        return false;
    });

    var show_childs=1;

    $("body").on("click",".show_hide_childs",function(){



        if (show_childs==1) {
            $(this).parents('.btm_level').first().children(".btm_sortable_ul").hide(500);
            show_childs=0;
        }else{
            $(this).parents('.btm_level').first().children(".btm_sortable_ul").show(500);
            show_childs=1;
        }


        return false;
    });

    $(".save_sortable_menu").click(function(){

        var parent_lis=$(".btm_sortable_ul").first().children("li");
        var menu_objs=save_new_menu(parent_lis);
        var menu_id=$("#menu_id_val").val();
        var menu_title=$("#menu_title_val").val();
        var lang_id=$("#lang_id_val").val();


        console.log(menu_title);

        var this_element = $(this);
        this_element.append("<img src='" + base_url + "img/ajax-loader.gif' class='ajax_loader_class' width='20'>");

        //save menu_objs in db
        $.ajax({
            url: base_url2 + 'admin/menus/save_sortable_menu',
            type: 'POST',
            data: {'_token': _token, 'menu_json': menu_objs, 'menu_id': menu_id,'menu_title':menu_title,'lang_id':lang_id},
            success: function (data) {
                $(".ajax_loader_class").hide();

                console.log(data);
                var json_data = JSON.parse(data);

                console.log(json_data);

                if (typeof (json_data) != "undefiend") {
                    if (typeof (json_data.success) != "undefined") {
                        this_element.append(" " + json_data.success);
                    }

                    if (typeof (json_data.error) != "undefined") {
                        this_element.append(" " + json_data.error);
                    }
                }
            }
        });





        return false;
    });


});