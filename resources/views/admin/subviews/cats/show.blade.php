@extends('admin.main_layout')

@section('subview')

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <style>
        .modal_sorted_page_body{
            max-height: 500px;
            overflow-y: scroll;
        }
        #pages_sortable {
            list-style-type: none;
            margin: 0;
            padding: 0;
            width: 60%;
            margin-left: 22%
        }
        #pages_sortable li {
            margin: 0 3px 3px 3px;
            font-size: 1.4em;
            height: 40px;
            cursor: move;
        }
        #pages_sortable li span {
            margin-left: -1.3em;
        }
    </style>

    <div class="panel panel-info">
        <div class="panel-heading">
            {!! transform_underscore_text($cat_type) !!} {{($cat_type != "book")?'Categories':''}}
        </div>
        <div class="panel-body">
            <table id="cat_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Preview Image</th>
                        <th>Name</th>
                        <?php if($cat_type=="book"): ?>
                        <th>Book Owner</th>
                        <th>Related Activity</th>
                        <th>Manage Pages</th>
                        <th>Views Count</th>
                        <?php endif; ?>
                        <?php if($cat_type=="activity"): ?>
                        <th>Parent Name</th>
                        <th>Action</th>
                        <?php endif; ?>
                        <th>Order</th>
                        <th>Hide</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>

                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Preview Image</th>
                        <th>Name</th>
                        <?php if($cat_type=="book"): ?>
                        <th>Book Owner</th>
                        <th>Related Activity</th>
                        <th>Manage Pages</th>
                        <th>Views Count</th>
                        <?php endif; ?>
                        <?php if($cat_type=="activity"): ?>
                        <th>Parent Name</th>
                        <th>Action</th>
                        <?php endif; ?>
                        <th>Order</th>
                        <th>Hide</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </tfoot>

                <tbody id="sortable">
                    <?php foreach ($all_cats as $key => $cat): ?>
                        <tr id="row<?= $cat->cat_id ?>" data-fieldname="cat_order" data-parentid="<?= $cat->parent_id ?>"  data-itemid="<?= $cat->cat_id ?>" data-tablename="App\models\category_m">
                        <td><?=$key+1?></td>
                        <td>
                            <img src="{{get_image_or_default($cat->small_img_path)}}" width="100">
                        </td>
                        <td><?= $cat->cat_name ?></td>
                        <?php if($cat_type=="book"): ?>
                            <td><?php echo $cat->owner_name ?></td>
                            <td><?php echo $cat->parent_cat_name ?></td>
                            <td>
                                <a href="{{url("admin/books/pages/$cat->cat_id")}}" class="btn btn-success"><i class="fa fa-newspaper-o"></i> Manage Pages </a>

                                <?php if(false): ?>
                                    <a class="btn btn-success add_new_page" title="Add New Page" data-book_name="{{$cat->cat_name}}" data-book_id="{{$cat->cat_id}}"><i class="fa fa-plus"></i></a>
                                    <a class="btn btn-info add_new_page load_old_pages" title="Edit Existing Pages" data-book_name="{{$cat->cat_name}}" data-book_id="{{$cat->cat_id}}"><i class="fa fa-edit"></i></a>
                                    <a class="btn btn-info order_pages" title="Order Existing Pages" data-book_name="{{$cat->cat_name}}" data-book_id="{{$cat->cat_id}}"><i class="fa fa-refresh"></i></a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="label label-info">{{$cat->cat_views}} <i class="fa fa-eye"></i></span>
                            </td>
                        <?php endif; ?>

                        <?php if($cat_type=="activity"): ?>
                        <td>
                            <?php if ($cat->parent_id > 0): ?>
                                <?php echo $cat->parent_cat_name ?>
                            <?php else: ?>
                                No Parent
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($cat->parent_id==0): ?>
                            <a href="<?=url("admin/category/$cat->cat_type/$cat->cat_id")?>">Show Sub-Categories</a>
                            <?php elseif(false && $cat->parent_id>0): ?>
                            <a href="<?=url("admin/pages/show_all/article/$cat->cat_id")?>">Show Book</a>
                            <?php endif; ?>
                        </td>
                        <?php endif; ?>

                        <td><?=$cat->cat_order+1?></td>

                        <td>
                            <?php
                            echo generate_multi_accepters(
                                $accepturl="",
                                $item_obj=$cat,
                                $item_primary_col="cat_id",
                                $accept_or_refuse_col="hide_cat",
                                $model='App\models\category_m',
                                $accepters_data=["0"=>"Show","1"=>"Hide"]
                            );

                            ?>
                        </td>
                        <td><a href="<?= url("admin/category/save_cat/$cat->cat_type/$cat->cat_id") ?>"><span class="label label-info"> Edit <i class="fa fa-edit"></i></span></a></td>
                        <td><a href='#' class="general_remove_item" data-deleteurl="<?= url("/admin/category/delete_cat") ?>" data-tablename="App\models\category_m"  data-itemid="<?= $cat->cat_id ?>"><span class="label label-danger"> Delete <i class="fa fa-remove"></i></span></a></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>

            </table>

            <div class="col-md-6 col-md-offset-3">
                <button class="btn btn-primary btn-block reorder_items">Re-Order</button>
            </div>

        </div>
    </div>


    <!--new_editor-->
    <script src="{{url("/public_html/ckeditor/ckeditor.js")}}" type="text/javascript"></script>
    <script src="{{url("/public_html/ckeditor/adapters/jquery.js")}}" type="text/javascript"></script>

    <!-- Modal -->
    <div class="modal fade" id="manage_pages" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document" style="text-align: center;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title get_book_name" id="myModalLabel"></h4>
                </div>
                <div class="modal-body modal_page_body">

                    <div class="row load_pages_options">

                    </div>

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <?php foreach($lang_ids as $lang_key=>$lang_item): ?>
                            <?php
                            $lang_id=$lang_item->lang_id;
                            ?>
                            <li role="presentation" class="{{($lang_key == 0)?"active":""}}">
                                <a href="#tap-{{$lang_id}}" aria-controls="tap-{{$lang_id}}" role="tab" data-toggle="tab">
                                    <img src="<?=get_image_or_default($lang_item->lang_img_path)?>" width="25">
                                    {{$lang_item->lang_title}}</a>
                            </li>
                        <?php endforeach;?>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <input type="hidden" class="get_lang_ids" value="{{implode(',',convert_inside_obj_to_arr($lang_ids,"lang_id"))}}">
                        <?php foreach($lang_ids as $lang_key=>$lang_item): ?>
                            <?php
                            $lang_id=$lang_item->lang_id;
                            ?>
                            <div role="tabpanel" class="tab-pane {{($lang_key == 0)?"active":""}}" id="tap-{{$lang_id}}">

                                <?php

                                $field_name = "page_body_$lang_item->lang_id";

                                $normal_tags=["$field_name"];

                                $attrs = generate_default_array_inputs_html(
                                    $normal_tags,
                                    $translate_data="",
                                    "yes",
                                    $required="required"
                                );

                                $attrs[0]["$field_name"]="Page Body";
                                $attrs[3]["$field_name"]="textarea";
                                $attrs[5]["$field_name"].=" ckeditor";
//
//                                foreach ($attrs[1] as $key => $value) {
//                                    $attrs[1][$key].="[]";
//                                }

                                echo
                                generate_inputs_html(
                                    reformate_arr_without_keys($attrs[0]),
                                    reformate_arr_without_keys($attrs[1]),
                                    reformate_arr_without_keys($attrs[2]),
                                    reformate_arr_without_keys($attrs[3]),
                                    reformate_arr_without_keys($attrs[4]),
                                    reformate_arr_without_keys($attrs[5])
                                );

                                ?>

                            </div>
                        <?php endforeach;?>
                    </div>


                </div>
                <div class="modal-footer">
                    <div class="col-md-6 col-md-offset-2 save_page_data_msg"></div>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_page_data">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="sort_pages_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document" style="text-align: center;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title get_sorted_book_name" id="myModalLabel"></h4>
                </div>
                <div class="modal-body modal_sorted_page_body">

                </div>
                <div class="modal-footer">
                    <div class="col-md-6 col-md-offset-2 save_sorted_pages_msg"></div>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_sorted_pages">Save changes</button>
                </div>
            </div>
        </div>
    </div>

@endsection
