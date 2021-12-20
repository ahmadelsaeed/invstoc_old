@extends('admin.main_layout')

@section('subview')

    <div class="panel panel-info">
        <div class="panel-heading">
            Filteration with Date Range
        </div>
        <div class="panel-body">

            <form action="{{url("admin/pages/show_all/stock_exchange")}}">

                <div class="col-md-12">

                    <div class="col-md-6">
                        <label for="">
                            <b>From Date :</b>
                        </label>
                        <input type="date" required class="form-control" name="from_date" value="{{date("Y-m-d")}}">
                    </div>
                    <div class="col-md-6">
                        <label for="">
                            <b>To Date :</b>
                        </label>
                        <input type="date" required class="form-control" name="to_date" value="{{date("Y-m-d")}}">
                    </div>
                    <div class="col-md-12">
                        <button class="btn btn-info">Show Results</button>
                    </div>

                </div>

            </form>

        </div>
    </div>

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
                        <td>Datetime</td>
                        <td>Currency</td>
                        <td>Importance</td>
                        <td>Event Title</td>
                        <td>Current Value</td>
                        <td>Expected Value</td>
                        <td>Previous Value</td>
                        <td>Hide?</td>
                        <td>Edit</td>
                        <td>delete</td>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($pages as $key => $page): ?>
                    <tr id="row<?= $page->page_id ?>">
                        <td><?= $key+1; ?></td>
                        <td>{{$page->event_datetime}}</td>
                        <td>
                            <img src="{{get_image_or_default($page->currency_img_path)}}" width="25">
                            {{$page->cur_to}}
                        </td>
                        <td>
                            <?php
                            $level_img = "";
                            if ($page->importance_degree == 0 && isset($events_keywords->strong_img->path))
                            {
                                $level_img = get_image_or_default($events_keywords->strong_img->path);
                            }
                            if ($page->importance_degree == 1 && isset($events_keywords->middle_img->path))
                            {
                                $level_img = get_image_or_default($events_keywords->middle_img->path);
                            }
                            if ($page->importance_degree == 2 && isset($events_keywords->weak_img->path))
                            {
                                $level_img = get_image_or_default($events_keywords->weak_img->path);
                            }
                            if ($page->importance_degree == 3 && isset($events_keywords->very_weak_img->path))
                            {
                                $level_img = get_image_or_default($events_keywords->very_weak_img->path);
                            }
                            if ($page->importance_degree == 4 && isset($events_keywords->not_important_img->path))
                            {
                                $level_img = get_image_or_default($events_keywords->not_important_img->path);
                            }
                            ?>
                            <img src="{{$level_img}}" width="25" height="25">
                        </td>
                        <td>{{$page->page_title}}</td>
                        <td>
                            <span class="general_self_edit" data-url="<?= url("/general_self_edit") ?>"
                                  data-row_id="<?= $page->page_id ?>"
                                  data-tablename="App\models\pages\pages_m"
                                  data-field_name="current_value"
                                  data-input_type="text"
                                  data-row_primary_col="page_id"
                                  data-field_value="<?= $page->current_value ?>"
                                  title="Click To Edit"> <?= $page->current_value ?>
                                <i class="fa fa-edit"></i></span>
                        </td>
                        <td>
                            <span class="general_self_edit" data-url="<?= url("/general_self_edit") ?>"
                                  data-row_id="<?= $page->page_id ?>"
                                  data-tablename="App\models\pages\pages_m"
                                  data-field_name="expected_value"
                                  data-input_type="text"
                                  data-row_primary_col="page_id"
                                  data-field_value="<?= $page->expected_value ?>"
                                  title="Click To Edit"> <?= $page->expected_value ?>
                                <i class="fa fa-edit"></i></span>
                        </td>
                        <td>
                            <span class="general_self_edit" data-url="<?= url("/general_self_edit") ?>"
                                  data-row_id="<?= $page->page_id ?>"
                                  data-tablename="App\models\pages\pages_m"
                                  data-field_name="previous_value"
                                  data-input_type="text"
                                  data-row_primary_col="page_id"
                                  data-field_value="<?= $page->previous_value ?>"
                                  title="Click To Edit"> <?= $page->previous_value ?>
                                <i class="fa fa-edit"></i></span>
                        </td>
                        <td>
                            <?php
                                echo generate_multi_accepters(
                                    $accepturl="",
                                    $item_obj=$page,
                                    $item_primary_col="page_id",
                                    $accept_or_refuse_col="hide_page",
                                    $model='App\models\pages\pages_m',
                                    $accepters_data=["1"=>"Hide","0"=>"Show"]
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

    <div class="panel panel-warning">
        <div class="panel-heading">
            Delete from Date Range
        </div>
        <div class="panel-body">

            <form action="{{url("admin/pages/show_all/stock_exchange")}}" method="POST">

                <div class="col-md-12">

                    <div class="col-md-6">
                        <label for="">
                            <b>From Date :</b>
                        </label>
                        <input type="date" required class="form-control" name="from_date" value="{{date("Y-m-d")}}">
                    </div>
                    <div class="col-md-6">
                        <label for="">
                            <b>To Date :</b>
                        </label>
                        <input type="date" required class="form-control" name="to_date" value="{{date("Y-m-d")}}">
                    </div>
                    <div class="col-md-12">
                        {{csrf_field()}}
                        <button class="btn btn-danger delete_stock_exchange">Delete</button>
                    </div>

                </div>

            </form>

        </div>
    </div>


@endsection
