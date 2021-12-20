@extends('admin.main_layout')

@section('subview')

    <div class="panel panel-info">
        <div class="panel-heading">
            Pages for Book {{$book_data->cat_name}}
        </div>
        <div class="panel-body">
            <div class="col-md-12" style="    overflow-x: scroll;">

                <table id="cat_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <td>#</td>
                        <td>Page Number</td>
                        <td>Page Visits</td>
                        <td>Language</td>
                        <td>Hide?</td>
                        <td>Edit</td>
                        <td>delete</td>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($book_pages as $key => $page): ?>

                    <tr id="row<?= $page->book_page_id ?>">
                        <td><?= $key+1; ?></td>

                        <td>N.<?= $page->book_page_number ?>
                            <span class="label label-info">lang-{{$page->lang_title}}</span>
                        </td>
                        <td><?= $page->book_page_visits ?> <i class="fa fa-eye"></i> </td>
                        <td>
                            {{$page->lang_text}}
                            <img src="{{get_image_or_default($page->lang_img_path)}}" width="50" height="50">
                        </td>
                        <td>
                            <?php
                            echo generate_multi_accepters(
                                $accepturl="",
                                $item_obj=$page,
                                $item_primary_col="book_page_id",
                                $accept_or_refuse_col="hide_page",
                                $model='App\models\books\book_pages_m',
                                $accepters_data=["1"=>"Yes","0"=>"No"]
                            );
                            ?>
                        </td>
                        <td><a href="<?= url("admin/books/save_book_page/$book_data->cat_id/$page->book_page_id") ?>"><span class="label label-info">Edit <i class="fa fa-edit"></i></span></a></td>
                        <td><a href='#' class="general_remove_item" data-tablename="App\models\books\book_pages_m" data-deleteurl="<?= url("/admin/books/remove_book_page") ?>" data-itemid="<?= $page->book_page_id ?>"><span class="label label-danger">Delete <i class="fa fa-remove"></i></span></a></td>
                    </tr>
                    <?php endforeach ?>
                    </tbody>

                </table>

            </div>

            <div class="col-md-12">
                <a href="{{url("admin/books/save_book_page/$book_data->cat_id")}}" class="btn btn-info"> Add New Page </a>
            </div>

        </div>
    </div>



@endsection
