@extends('admin.main_layout')

@section('subview')

    <div class="panel panel-info">
        <div class="panel-heading">
            {{$page_type}}
        </div>
        <div class="panel-body">
            <div class="col-md-12" style="    overflow-x: scroll;">

                <table id="cat_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <td>#</td>
                        <td>Image</td>
                        <td>Name</td>
                        <?php if($page_type == "default"): ?>
                        <td>Show in Sidebar ?</td>
                        <td>Show as Privacy ?</td>
                        <td>Show as Existing Account ?</td>
                        <?php endif; ?>
                        <?php if($page_type == "company"): ?>
                        <td>Users Accounts</td>
                        <?php endif; ?>
                        <?php if($page_type == "news"): ?>
                        <td>Show in Homepage ?</td>
                        <?php endif; ?>
                        <td>Hide?</td>
                        <td>Edit</td>
                        <td>delete</td>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($pages as $key => $page): ?>
                        <?php
                            $img_url = url('public_html/img/no_img.png');
                            if (file_exists($page->small_img_path))
                            {
                                $img_url = url($page->small_img_path);
                            }

                            if($page->page_type=="default"){
                                $url=url("/$page->page_slug");
                            }
                            else{
                                $url=url(
                                    "/"."$page->parent_cat_slug".
                                    "/"."$page->page_slug"
                                );
                            }

                        ?>

                    <tr id="row<?= $page->page_id ?>">
                        <td><?= $key+1; ?></td>

                        <td><img src="<?= url($img_url) ?>" width="50"></td>
                        <td><?= $page->page_title ?></td>
                        <?php if($page_type == "default"): ?>
                        <td>
                            <?php
                            echo generate_multi_accepters(
                                $accepturl="",
                                $item_obj=$page,
                                $item_primary_col="page_id",
                                $accept_or_refuse_col="show_in_sidebar",
                                $model='App\models\pages\pages_m',
                                $accepters_data=["1"=>"Yes","0"=>"No"]
                            );
                            ?>
                        </td>
                        <td>
                            <?php
                            echo generate_multi_accepters(
                                $accepturl="",
                                $item_obj=$page,
                                $item_primary_col="page_id",
                                $accept_or_refuse_col="show_in_privacy",
                                $model='App\models\pages\pages_m',
                                $accepters_data=["1"=>"Yes","0"=>"No"]
                            );
                            ?>
                        </td>
                        <td>
                            <?php
                            echo generate_multi_accepters(
                                $accepturl="",
                                $item_obj=$page,
                                $item_primary_col="page_id",
                                $accept_or_refuse_col="show_in_existing",
                                $model='App\models\pages\pages_m',
                                $accepters_data=["1"=>"Yes","0"=>"No"]
                            );
                            ?>
                        </td>
                        <?php endif; ?>

                        <?php if($page_type == "company"): ?>
                        <td>
                            <a href="<?= url("admin/company/users_accounts/$page->page_id") ?>"><span class="label label-info"><i class="fa fa-wrench"></i> Manage </span></a>
                        </td>
                        <?php endif; ?>

                        <?php if($page_type == "news"): ?>
                        <td>
                            <?php

                            echo generate_multi_accepters(
                                $accepturl="",
                                $item_obj=$page,
                                $item_primary_col="page_id",
                                $accept_or_refuse_col="show_in_homepage",
                                $model='App\models\pages\pages_m',
                                $accepters_data=["1"=>"Yes","0"=>"No"]
                            );
                            ?>
                        </td>
                        <?php endif; ?>
                        <td>
                            <?php
                                echo generate_multi_accepters(
                                    $accepturl="",
                                    $item_obj=$page,
                                    $item_primary_col="page_id",
                                    $accept_or_refuse_col="hide_page",
                                    $model='App\models\pages\pages_m',
                                    $accepters_data=["1"=>"Yes","0"=>"No"]
                                );
                            ?>
                        </td>
                        <td><a href="<?= url("admin/pages/save_page/$page->page_type/$page->page_id") ?>"><span class="label label-info">Edit <i class="fa fa-edit"></i></span></a></td>
                        <td><a href='#' class="general_remove_item" data-tablename="App\models\pages\pages_m" data-deleteurl="<?= url("/admin/pages/remove_page") ?>" data-itemid="<?= $page->page_id ?>"><span class="label label-danger">Delete <i class="fa fa-remove"></i></span></a></td>
                    </tr>
                    <?php endforeach ?>
                    </tbody>

                </table>

            </div>

            <div class="col-md-12" style="text-align: center;">
                <?php if(!empty($pages_pagination)): ?>
                {{$pages_pagination->appends(\Illuminate\Support\Facades\Input::except('page'))}}
                <?php endif; ?>
            </div>

        </div>
    </div>



@endsection
