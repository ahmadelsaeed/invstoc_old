$(function () {

    var base_url2 = $(".url_class").val();
    var base_url = base_url2 + "/public_html/";

    var base_url3 = $(".url_class_2").val();
    var base_url_new = base_url3 + "/public_html/";

    var _token = $(".csrf_input_class").val();

    $.each($(".ckeditor"),function(key,val){

        // $(this).attr("id","ck_"+key);

        var get_id = $(this).attr("id");
        var str_id =  get_id.toString();

        if(str_id.indexOf('[]') >= 0)
        {
            $(this).attr("id","ck_"+key);
        }

        CKEDITOR.replace( $(this).attr("id"), {
            filebrowserBrowseUrl: base_url_new+'/browse',
            filebrowserUploadUrl: base_url_new+'/upload',
        });

    });

   // console.log("admin" + base_url2);
//    console.log(_token);

    //General functions
    if($('#cat_table').length > 0)
    {
        $('#cat_table').DataTable();
    }

    if($("input[type='date']").length){
        $("input[type='date']").datetimepicker({format: 'YYYY-MM-DD'});

    }

    if ($('#subscribe_table').length > 0)
    {
        // console.log(base_url2+"/uploads/datatables_json/subscribe.json");
        $('#subscribe_table').DataTable(
            {
                "ajax": {
                    "url": base_url_new+"/uploads/datatables_json/subscribe.json",
                    "type": "POST"
                }

            }
        );

    }


    if ($(".reorder_items").length) {
        $("#sortable").sortable();
        $("#sortable").disableSelection();

        $(".reorder_items").click(function () {
            var items = [];
            var table_name;
            var field_name;

            $.each($("#sortable").children(), function (index, value) {
                var item_id = $(this).data("itemid");
                table_name = $(this).data("tablename");
                field_name = $(this).data("fieldname");

                var item_order = index;

                items.push([item_id, item_order]);
            });

            if (typeof(field_name) == "undefined") {
                field_name = "order";
            }

            console.log(JSON.stringify(items));

            var this_element = $(this);
            this_element.append("<img src='" + base_url_new + "img/ajax-loader.gif' class='ajax_loader_class' width='20'>");

            console.log(table_name);
//            console.log({'csrf_4cards_seoera': csrf_4cards_seoera, 'items': items, 'table_name': table_name , 'field_name':field_name});

            $.ajax({
                url: base_url2 + '/reorder_items',
                type: 'POST',
                data: {'_token':_token, 'items': items, 'table_name': table_name , 'field_name':field_name},
                success: function (data) {
                    $(".ajax_loader_class").hide();
                    console.log(data);
                    var json_data = JSON.parse(data);

                    console.log(json_data);

                    if (typeof (json_data) != "undefiend") {
                        if (typeof (json_data.success) != "undefined") {
                            this_element.html(" Re-Order " + json_data.success);
                            window.location.reload();
                        }

                        if (typeof (json_data.error) != "undefined") {
                            this_element.html(" Re-Order " + json_data.error);
                        }
                    }
                }
            });


            return false;
        });
    }

    // Start General Self Edit Item

         // Show input text field
        $('body').on("click",".general_self_edit",function () {

            var this_element = $(this);
            var old_value = this_element.attr("data-field_value");
            var table = this_element.attr("data-tablename");
            var field = this_element.attr("data-field_name");
            var row = this_element.attr("data-row_id");
            var url = this_element.attr("data-url");
            var input_type = this_element.attr("data-input_type");
            var row_primary_col = this_element.attr("data-row_primary_col");

            var additional_params = "";

            if (input_type == "number")
            {
                additional_params = ' min="0" ';
            }

            if (input_type == "textarea")
            {
                this_element.parent().html('<textarea '+additional_params+' data-input_type="'+input_type+'" data-url="'+url+'" class="form-control self_edit_action" data-old_value="'+old_value+'" data-row_primary_col="'+row_primary_col+'" data-row_id="'+row+'" data-tablename="'+table+'" data-field_name="'+field+'" title="Press Enter To Save" >'+old_value+'</textarea>')
            }
            else{
                this_element.parent().html('<input type="'+input_type+'" '+additional_params+'  data-input_type="'+input_type+'" value="'+old_value+'"  data-url="'+url+'" class="form-control self_edit_action" data-old_value="'+old_value+'" data-row_primary_col="'+row_primary_col+'" data-row_id="'+row+'" data-tablename="'+table+'" data-field_name="'+field+'" title="Press Enter To Save" />')
            }



        });

        // apply action on enter
        // $(".self_edit_action").keyup(function(event){
        $('body').on("keyup",".self_edit_action",function (event) {

            var this_element = $(this);
            var old_value = this_element.attr("data-old_value");
            var new_value = this_element.val();
            var table = this_element.attr("data-tablename");
            var field = this_element.attr("data-field_name");
            var row = this_element.attr("data-row_id");
            var url = this_element.attr("data-url");
            var input_type = this_element.attr("data-input_type");
            var row_primary_col = this_element.attr("data-row_primary_col");

            var object = {};
            object._token = _token;
            object.item_id = row;
            object.table_name = table;
            object.field_name = field;
            object.value = new_value;
            object.input_type = input_type;
            object.row_primary_col = row_primary_col;
            if (event.which==13) {
                console.log(object);

                console.log(new_value);

                this_element.attr("disabled","disabled");

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: object,
                    success: function (data) {
                        console.log(data);
                        var returned_data = JSON.parse(data);
                        this_element.removeAttr("disabled");
                        if (typeof (returned_data.msg)!= "undefined") {
                            this_element.parent().html(returned_data.msg);
                        }

                    }
                });




            }
        });

    // End General Self Edit Item


    $('body').on("click", ".general_remove_item", function () {
        var confirm_res = confirm("Are you Sure?");
        if (confirm_res == true) {
            console.log(confirm_res);
            var item_id = $(this).data("itemid");
            var delete_url = $(this).data("deleteurl");
            var table_name = $(this).data("tablename");
            var tr_id = $(this).data("trid");

            var this_item=$(this);

            if (typeof(tr_id) == "undefined") {
                tr_id = "row";
            }

            //show load img
            $(this).append("<img src='" + base_url_new + "img/ajax-loader.gif' class='ajax_loader_class' width='20'>");
            $.ajax({
                url: delete_url,
                type: 'POST',
                data: {'_token': _token, 'item_id': item_id, 'table_name': table_name},
                success: function (data) {
                    console.log(data);
                    var returned_data = JSON.parse(data);

                    if (returned_data.deleted == "yes") {
                        console.log('#row' + item_id);

                        $('#'+ tr_id + item_id).fadeOut(400);
                        $("tr[data-parentid=" + item_id + "]").fadeOut(400);
                    }else{
                        this_item.html(returned_data.deleted);
                    }

                    if(typeof (returned_data.msg)!="undefined"){
                        this_item.html(returned_data.msg);
                    }

                }
            });

        }//end confirmation if

        return false;
    });

    $('body').on("click", ".general_remove_item_ajax", function () {
        var confirm_res = confirm("Are you Sure?");
        if (confirm_res == true) {
            console.log(confirm_res);
            var item_id = $(this).data("itemid");
            var delete_url = $(this).data("deleteurl");
            var table_name = $(this).data("tablename");
            var tr_id = $(this).data("trid");

            var this_item=$(this);

            if (typeof(tr_id) == "undefined") {
                tr_id = "row";
            }

            //show load img
            $(this).append("<img src='" + base_url_new + "img/ajax-loader.gif' class='ajax_loader_class' width='20'>");
            $.ajax({
                url: delete_url,
                type: 'POST',
                // headers:
                // {
                //     '_token': _token
                // },
                data: {'_token': _token, 'item_id': item_id, 'table_name': table_name},
                success: function (data) {
                    console.log(data);
                    var returned_data = JSON.parse(data);

                    if (returned_data.deleted == "yes") {
                        console.log('#row' + item_id);

                        $('#'+ tr_id + item_id).fadeOut(400);
                        this_item.parent().parent().fadeOut(400);
                    }else{
                        this_item.html(returned_data.deleted);
                    }

                }
            });

        }//end confirmation if

        return false;
    });


    $('body').on("click", ".remove_item", function () {
        var confirm_res = confirm("Are you Sure?");
        if (confirm_res == true) {
            console.log(confirm_res);
            var item_id = $(this).data("itemid");
            var delete_url = $(this).data("deleteurl");
            //show load img
            $(this).append("<img src='" + base_url_new + "img/ajax-loader.gif' class='ajax_loader_class' width='20'>");
            $.ajax({
                url: delete_url,
                type: 'POST',
                data: {'_token': _token, 'item_id': item_id},
                success: function (data) {
                    console.log(data);
                    var returned_data = JSON.parse(data);

                    if (returned_data.deleted == "yes") {
                        $('#row' + item_id).fadeOut(400);
                        $("tr[data-parentid=" + item_id + "]").fadeOut(400);
                    }

                }
            });

        }//end confirmation if

        return false;
    });


    // accept item
    $('body').on("click", ".general_accept_item", function () {
        var confirm_res = confirm("Are you Sure?");
        if (confirm_res == true) {
            console.log(confirm_res);

            var this_element = $(this);
            var object = {};

            var item_id = this_element.data("itemid");
            var accept_url = this_element.data("accepturl");
            var table_name = this_element.data("tablename");
            var field_name = this_element.data("fieldname");
            var accept = this_element.attr("data-accept");

            object._token = _token;
            object.item_id = item_id;
            object.accept_url = accept_url;
            object.table_name = table_name;
            object.field_name = field_name;
            object.accept = accept;

            var email = this_element.data("send_email");
            var msg_target = this_element.data("msg_target");
            if (typeof(email) != "undefined") {
                object.email = email;
                object.msg_target = msg_target;
            }

            //show load img
            this_element.append("<img src='" + base_url_new + "img/ajax-loader.gif' class='ajax_loader_class' width='20'>");

            $.ajax({
                url: accept_url,
                type: 'POST',
                data: object,
                success: function (data) {
                    console.log(data);
                    var returned_data = JSON.parse(data);

                    if (returned_data.success != "error") {
                        $('.ajax_loader_class').remove();
                        this_element.children(".inside_review_status").html(returned_data.status);
                        $(this_element).attr('data-accept',returned_data.new_accept);
                    }

                }
            });

        }//end confirmation if

        return false;
    });

    //new accept item
    $('body').on("click", ".new_general_accept_item", function () {
        var confirm_res = confirm("Are you Sure?");
        if (confirm_res == true) {
            console.log(confirm_res);

            var this_element = $(this);
            var object = {};

            var item_id = this_element.data("itemid");
            var accept_url = this_element.data("accepturl");
            var table_name = this_element.data("tablename");
            var field_name = this_element.data("fieldname");
            var accept = this_element.attr("data-accept");
            var acceptersdata = this_element.attr("data-acceptersdata");
            var item_primary_col = this_element.attr("data-item_primary_col");

            object._token = _token;
            object.item_id = item_id;
            object.accept_url = accept_url;
            object.table_name = table_name;
            object.field_name = field_name;
            object.accept = accept;
            object.acceptersdata = acceptersdata;
            object.item_primary_col = item_primary_col;
            object.accept_url = accept_url;

            //show load img
            this_element.append("<img src='" + base_url_new + "img/ajax-loader.gif' class='ajax_loader_class' width='20'>");

            $.ajax({
                url: accept_url,
                type: 'POST',
                data: object,
                success: function (data) {
                    console.log(data);
                    var returned_data = JSON.parse(data);

                    if (typeof (returned_data.msg)!= "undefined") {
                        $('.ajax_loader_class',this_element).remove();
                        this_element.parents(".parent_accepters_div").html(returned_data.msg);
                    }

                }
            });

        }//end confirmation if

        return false;
    });



    // change the old data with new data
    $('body').on("click", ".apply_changes", function () {
        var confirm_res = confirm("Are you Sure?");
        if (confirm_res == true) {
            console.log(confirm_res);

            var this_element = $(this);

            var shop_id = this_element.data("itemid");
            var changeurl = this_element.data("changeurl");
            var shopdata = this_element.data("shopdata");

            //show load img
            this_element.append("<img src='" + base_url_new + "img/ajax-loader.gif' class='ajax_loader_class' width='20'>");

            $.ajax({
                url: changeurl,
                type: 'POST',
                data: {'_token': _token, 'shopdata': shopdata},
                success: function (data) {
//                    console.log(data);
                    var returned_data = JSON.parse(data);
                    console.log(returned_data.mydata);
                    if (returned_data.success != "error") {
                        $('#row'+shop_id).fadeOut(400);

                    }

                }
            });

        }//end confirmation if

        return false;
    });




    $("body").on("click",".show_general_data",function(){
        var data=$(this).data("alldata");

        var html_tags="";

        $.each(data,function(index,value){
            console.log(typeof (value));
            if(typeof (value)=="object"){
                var object_val="";
                $.each(value,function(i,v){
                    object_val=object_val+i+" : "+v+" <br>";
                });
                value=object_val;
            }

            html_tags+='<div class="col-md-12">';
                html_tags+='<div class="col-md-3">';
                    html_tags+='<p style="text-transform: capitalize;">'+index.replace("_"," ")+':</p>';
                html_tags+='</div>';
                html_tags+='<div class="col-md-9">';
                    html_tags+='<p>'+value+'</p>';
                html_tags+='</div>';
            html_tags+='</div><hr>';

        });

        $("#general_show_all_data_modal .modal-body").html(html_tags);
        $("#general_show_all_data_modal").modal("show");

        return false;
    });

    // select multiple cities
    $(".select_block_pages").change(function(){
        var block_value=$(this).val();
        var block_text=$(".select_block_pages option:selected").text();

        if (block_text.length==0) {
            return;
        }

        var append_html="<li class='selected_block_div'>";
        append_html+="<input type='hidden' name='selected_blocks_arr[]' value='"+block_value+"'>";

        append_html+="<span>"+block_text+"</span>";
        append_html+='<a href="#" class="remove_block"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>';

        append_html+="</li>";

        $('.selected_blocks').append(append_html);
    });

    $('body').on("click",".remove_block",function(){
        $(this).parent().remove();
        return false;
    });


    $(".general_check_validation").click(function(){
        var this_element=$(this);
        var link=$(this).data("link");
        var form_id=$(this).data("formid");

        this_element.append("<img src='" + base_url_new + "img/ajax-loader.gif' class='ajax_loader_class' width='20'>");
        this_element.attr("disabled","disabled");


        var formElement = document.querySelector("#"+form_id);
        var request = new XMLHttpRequest();

        request.onreadystatechange=function(){
            if (request.readyState==4) {

                var data=JSON.parse(request.responseText);
                console.log(data);
                if (typeof (data)!="undefined") {
                    $('.ajax_loader_class').remove();

                    var show_msg = "<div class='alert alert-danger'>";
                    // console.log(data.msg_type);
                    // console.log(data.msg);
                    if(data.msg_type == "success")
                    {
                        show_msg = "<div class='alert alert-success'>";
                    }
                    // console.log(data.msg.length);
                    if(data.msg.length == 0)
                    {
                        show_msg += "Success Data Apply Save Now ...";
                    }
                    $.each(data.msg,function (i,v) {

                        show_msg += v + "<br>";

                    });
                    show_msg +="</div>";
                    $(".general_check_validation_msg").html(show_msg);
                    this_element.removeAttr("disabled");
                }
            }
        };

        request.open("POST", link);
        request.send(new FormData(formElement));


        return false;
    });


    // Send Custom Email
    $("body").on("click",".general_send_email",function(){
        var email=$(this).data("sender_email");
        var url=$(this).data("send_url");

        $('.submit_custom_email').attr('data-sender_email_modal',email);
        $('.submit_custom_email').attr('data-sender_url_modal',url);

        $("#send_email_modal .modal-title").html("Send Email to >> "+email);
        $("#send_email_modal").modal("show");

        $("#send_email_modal .modal-footer .show_errors").html("");

        return false;
    });

    $("body").on("click",".show_last_email",function(){
        var email_msg=$(this).data("last_email_msg");
        email_msg = JSON.parse(email_msg);

        CKEDITOR.instances['email_body_msg_id'].setData(email_msg);


        $("#show_email_msg .modal-title").html("Email Message");
        $("#show_email_msg").modal("show");

        return false;
    });

    $("body").on("click",".send_all_subscribers_email",function(){

        var confirm_res = confirm("Are you Sure To Send Email To All Subscribers ?");
        if (confirm_res == true) {

            var url=$(this).data("send_all_subscribers_url");
            var this_element = $(this);

            var object = {};
            object._token = _token;

            this_element.attr('disabled','disabled');

            //show load img
            this_element.append("<img src='" + base_url_new + "img/ajax-loader.gif' class='ajax_loader_class' width='20'>");

            $.ajax({
                url: url,
                type: 'POST',
                data: object,
                success: function (data) {
                    console.log(data);
                    var returned_data = JSON.parse(data);
                    console.log(returned_data.dump);
                    if (returned_data.success != "error") {
                        $('.ajax_loader_class').remove();
                        this_element.removeAttr("disabled");
                        $('.show_errors_msgs').html(returned_data.success);
                        location.href = returned_data.redirect_url;
                    }
                    else{
                        $('.ajax_loader_class').remove();
                        this_element.removeAttr("disabled");
                        $('.show_errors_msgs').html(returned_data.error);
                    }

                }
            });
        }

        return false;
    });

    $("body").on("click",".submit_custom_email",function(){

        var this_element = $(this)
        var url=this_element.data("sender_url_modal");

        var object = {};
        object._token = _token;
        object.email = this_element.attr("data-sender_email_modal");
        object.sender_email = $('.sender_email').val();
        object.email_subject = $('.email_subject').val();
        object.email_body = CKEDITOR.instances['email_body_id'].getData();

        this_element.attr('disabled','disabled');
//        console.log(object);

        //show load img
        this_element.append("<img src='" + base_url_new + "img/ajax-loader.gif' class='ajax_loader_class' width='20'>");

        $.ajax({
            url: url,
            type: 'POST',
            data: object,
            success: function (data) {
                console.log(data);
                var returned_data = JSON.parse(data);
                console.log(returned_data.dump);
                if (returned_data.success != "error") {
                    $('.ajax_loader_class').remove();
                    this_element.removeAttr("disabled");
                    $('.show_errors').html(returned_data.success);
                    console.log(returned_data.temp_body);
                }
                else{
                    $('.ajax_loader_class').remove();
                    this_element.removeAttr("disabled");
                    $('.show_errors').html(returned_data.error);
                }

            }
        });

        return false;
    });

    //END General functions


    var old_xhr;
    $("body").on("click",".general_ajax_method",function () {

        /*required data
         url,send_data_arr(fields you want to send),parent_div,show_msg_div

        */

        console.log("general_ajax_method");

        var this_element = $(this)
        this_element.attr('disabled','disabled');


        var url=this_element.data("url");
        var send_data_arr=this_element.data("send_data_arr");

        console.log(typeof (send_data_arr));

        var object = {};
        object._token = _token;
        if(typeof (send_data_arr)=="object"){
            $.each(send_data_arr,function (i,v) {
                object[i]=v;
            });
        }


        console.log(object);

        if(old_xhr){
            old_xhr.abort();
        }


        //show load img
        this_element.append("<img src='" + base_url_new + "img/ajax-loader.gif' class='ajax_loader_class' width='20'>");

        old_xhr=$.ajax({
            url: url,
            type: 'POST',
            data: object,
            success: function (data) {
                var returned_data = JSON.parse(data);
                if (typeof (returned_data.msg)!= "undefined") {
                    $('.ajax_loader_class').remove();
                    this_element.removeAttr("disabled");
                    $(
                        this_element.data("show_msg_div"),
                        this_element.parents(this_element.data("parent_div"))
                    ).html(returned_data.msg);
                }
            }
        });

        return false;


    });


    if($('.select_2_class').length){
        $('.select_2_class').select2();
    }


    if($("#disable_hide_input_id").length==0){
        $(".hide_input").parents(".form-group").hide();

    }

    if ($('.get_flash_message').length > 0) {
        var get_flash_message = $('.get_flash_message').val();
        var str = $('.get_flash_message').val();
        var type = 'success';
        var pure_msg = '<b style="font-size: 20px;">' + $(str).text() + '</b>';
        if (str.indexOf('alert-danger') > -1) {
            type = 'warning'
        } else if (str.indexOf('alert-warning') > -1) {
            type = 'warning'
        } else if (str.indexOf('alert-info') > -1) {
            type = 'info'
        }
        if (get_flash_message != "") {
            toastr.options = {
                "closeButton": !1,
                "debug": !1,
                "newestOnTop": !0,
                "progressBar": !0,
                "positionClass": "toast-top-full-width",
                "preventDuplicates": !1,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "5000",
                "timeOut": "5000",
                "extendedTimeOut": "5000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            toastr[type](pure_msg, '', toastr.options)
        }
    }

});
