@extends('admin.main_layout')

@section('subview')


    <!-- Select 2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <!-- END Select 2 -->

    <!--new_editor-->
    <script src="{{url("/public_html/ckeditor/ckeditor.js")}}" type="text/javascript"></script>
    <script src="{{url("/public_html/ckeditor/adapters/jquery.js")}}" type="text/javascript"></script>

    <script>
        $(function(){
            $('.select_user_langs').select2();
        });
    </script>

    <?php

    if (count($errors->all()) > 0)
    {
        $dump = "<div class='alert alert-danger'>";
        foreach ($errors->all() as $key => $error)
        {
            $dump .= $error." <br>";
        }
        $dump .= "</div>";

        echo $dump;
    }

    if (isset($success)&&!empty($success)) {
        echo $success;
    }
    ?>


    <?php

    $page_header="Adding New Page";
    $book_page_id="";
    $book_page_number = 0;
    if ($page_data!="") {
        $page_header="Editing Page N.$page_data->book_page_number";
        $book_page_id=$page_data->book_page_id;
        $book_page_number=$page_data->book_page_number;
    }

    ?>



    <?php if(count($get_all_book_pages)): ?>
    <div class="panel panel-warning">
        <div class="panel-heading" style="padding-bottom: 62px;">
            Move to another Page
        </div>
        <div class="panel-body">
            <div class="row">
                <form action="{{url("admin/books/save_book_page/$book_data->cat_id/$book_page_id")}}">

                    <div class="col-md-4">
                        <label for="">
                            <b>Select Page Number</b>
                        </label>
                        <select class="form-control select_user_langs show_book_pages" name="book_page_number">
                            <?php foreach($get_all_book_pages as $key => $value): ?>
                                <option value="{{$value->book_page_id}}" {{($book_page_number == $value->book_page_number)?"selected":""}}>Page N.{{$value->book_page_number}}</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-8">
                        <button class="btn btn-info" style="margin-top: 18px;">Go !</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="panel panel-info">
        <div class="panel-heading" style="padding-bottom: 62px;">
            <img src="{{get_image_or_default($book_data->small_img_path)}}" width="50" height="50">
             {{$book_data->cat_name}} >> <b>{{$page_header}}</b>
            <br>
        </div>
        <div class="panel-body">
            <div class="row">

                <form action="{{url("admin/books/save_book_page/$book_data->cat_id/$book_page_id")}}" method="POST" enctype="multipart/form-data">

                    <input type="hidden" class="book_id" value="{{$book_data->cat_id}}">
                    <input type="hidden" class="book_page_id" value="{{(empty($book_page_id)?0:$book_page_id)}}">
                    <input type="hidden" class="book_page_number" value="{{$book_page_number}}">

                    <?php if(!empty($book_page_id)): ?>
                    <input type="hidden" name="lang_id" value="{{$page_data->book_lang_id}}">
                    <?php endif; ?>

                    <div class="col-md-4 col-md-offset-2">
                        <?php

                            $all_langs_txt=convert_inside_obj_to_arr($all_langs,'lang_text');
                            $all_langs_values=convert_inside_obj_to_arr($all_langs,'lang_id');

                            echo generate_select_tags(
                                $field_name="lang_id",
                                $label_name="Select Language ?",
                                $text=$all_langs_txt,
                                $values=$all_langs_values,
                                $selected_value=[(isset($page_data->book_lang_id)?$page_data->book_lang_id:0)],
                                $class="form-control select_book_lang ",
                                $multiple="",
                                $required="required ".(!empty($book_page_id)?"disabled":""),
                                $disabled = "",
                                $data = ""
                            );

                        ?>
                    </div>

                    <div class="col-md-4">

                        <label for="">
                            <b>Change Page Number</b>
                        </label>
                        <select class="form-control select_user_langs show_book_pages" name="book_page_number">
                            <?php if(!count($get_all_book_pages)): ?>
                                <option value="1">First Page</option>
                                <?php else: ?>
                                    <?php foreach($get_all_book_pages as $key => $value): ?>
                                        <option value="{{$value->book_page_number}}" {{($book_page_number == $value->book_page_number)?"selected":""}}>Page N.{{$value->book_page_number}}</option>
                                    <?php endforeach; ?>
                                    <?php if($page_data==""): ?>
                                        <option value="{{(count($get_all_book_pages)+1)}}" selected>Last Page</option>
                                    <?php endif; ?>
                            <?php endif; ?>
                        </select>
                    </div>


                    <div class="page_blocks_container">

                        <?php if(count($page_blocks)): ?>

                            <?php foreach($page_blocks as $key => $page_block): ?>
                                <div class="col-md-12 page_block_item">
                                    <div class="panel panel-info">
                                        <div class="panel-heading" style="padding-bottom: 45px;">
                                            <span style="float:left;">
                                                <b>Page Block Item</b>
                                            </span>
                                            <button class="btn btn-danger remove_page_block_item" style="float: right">Remove Block <i class="fa fa-times"></i></button>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">

                                                <input type="hidden" name="book_page_block_id[]" class="book_page_block_id" value="{{$page_block->book_page_block_id}}">

                                                <div class="col-md-12">

                                                    <div class="col-md-3">
                                                        <label for=""><b>Choose Image</b></label>
                                                        <input type="file" name="block_img" class="block_img_file" accept="image/x-png,image/gif,image/jpeg,image/jpg">
                                                    </div>
                                                    <div class="col-md-3 preview_img">
                                                        <?php if(!empty($page_block->path)): ?>
                                                            <span class="remove_image_blow" style="cursor: pointer;"><i class="fa fa-times"></i></span>
                                                            <img src="{{get_image_or_default($page_block->path)}}" width="100" height="100">
                                                            <input type="hidden" name="old_block_img_id_{{$page_block->book_page_block_id}}" value="{{$page_block->block_img}}">
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="">
                                                            <b>Choose Image Direction</b>
                                                        </label>
                                                        <select name="block_img_position[]" class="form-control">
                                                            <option value="left" {{($page_block->block_img_position=="left")?"selected":""}}>Left</option>
                                                            <option value="center" {{($page_block->block_img_position=="center")?"selected":""}}>Center</option>
                                                            <option value="right" {{($page_block->block_img_position=="right")?"selected":""}}>Right</option>
                                                        </select>
                                                    </div>

                                                    <?php

                                                    $normal_tags=array("block_body[]");

                                                    $attrs = generate_default_array_inputs_html(
                                                        $normal_tags,
                                                        "",
                                                        "yes",
                                                        "required"
                                                    );

                                                    $page_block->block_body = str_replace('http://invstoc','https://www.invstoc',$page_block->block_body);

                                                    $attrs[0]["block_body[]"] = "Content Body";
                                                    $attrs[3]["block_body[]"] = "textarea";
                                                    $attrs[4]["block_body[]"] = $page_block->block_body;
                                                    $attrs[5]["block_body[]"] .= " ckeditor block_body_input";


                                                    echo
                                                    generate_inputs_html(
                                                        reformate_arr_without_keys($attrs[0]),
                                                        reformate_arr_without_keys($attrs[1]),
                                                        reformate_arr_without_keys($attrs[2]),
                                                        reformate_arr_without_keys($attrs[3]),
                                                        reformate_arr_without_keys($attrs[4]),
                                                        reformate_arr_without_keys($attrs[5]),
                                                        reformate_arr_without_keys($attrs[6])
                                                    );


                                                    ?>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        <?php else: ?>
                            <div class="col-md-12 page_block_item">
                                <div class="panel panel-info">
                                    <div class="panel-heading" style="padding-bottom: 45px;">
                                <span style="float:left;">
                                    <b>Page Block Item</b>
                                </span>
                                        <button class="btn btn-danger remove_page_block_item" style="float: right">Remove Block <i class="fa fa-times"></i></button>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">

                                            <div class="col-md-12">

                                                <div class="col-md-6">
                                                    <label for=""><b>Choose Image</b></label>
                                                    <input type="file" name="block_img" class="block_img_file" accept="image/x-png,image/gif,image/jpeg,image/jpg">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="">
                                                        <b>Choose Image Direction</b>
                                                    </label>
                                                    <select name="block_img_position[]" class="form-control">
                                                        <option value="left">Left</option>
                                                        <option value="center">Center</option>
                                                        <option value="right">Right</option>
                                                    </select>
                                                </div>

                                                <?php

                                                $normal_tags=array("block_body[]");

                                                $attrs = generate_default_array_inputs_html(
                                                    $normal_tags,
                                                    "",
                                                    "yes",
                                                    "required"
                                                );

                                                $attrs[0]["block_body[]"] = "Content Body";
                                                $attrs[3]["block_body[]"] = "textarea";
                                                $attrs[5]["block_body[]"] .= " ckeditor block_body_input";


                                                echo
                                                generate_inputs_html(
                                                    reformate_arr_without_keys($attrs[0]),
                                                    reformate_arr_without_keys($attrs[1]),
                                                    reformate_arr_without_keys($attrs[2]),
                                                    reformate_arr_without_keys($attrs[3]),
                                                    reformate_arr_without_keys($attrs[4]),
                                                    reformate_arr_without_keys($attrs[5]),
                                                    reformate_arr_without_keys($attrs[6])
                                                );


                                                ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>


                    </div>

                    <button type="button" class="btn btn-info add_new_page_block_item" style="float: right">Add New Block<i class="fa fa-plus"></i></button>


                    {{csrf_field()}}
                    <div class="col-md-12 save_new_page_div">
                        <input type="hidden" name="redirect_page_type" class="redirect_page_type" value="exit">
                        <button class="btn btn-primary save_new_page save_page_and_exit">Save And Exit</button>
                        <button class="btn btn-primary save_new_page save_page_and_new">Save And New</button>
                    </div>

                </form>

            </div>

        </div>
    </div>



@endsection
