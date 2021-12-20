@extends('admin.main_layout')

@section('subview')


<div class="panel panel-info">
    <div class="panel-heading">
        الاشعارات
    </div>
    <div class="panel-body">
        <table id="cat_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <td>#</td>
                <td>الاشعارات</td>
                <td>التاريخ</td>
                <td>مسح</td>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($all_notifications as $key => $not): ?>
            <tr not_id="row<?= $not->not_id ?>">
                <td><?= $key++; ?></td>
                <td><?= $not->not_title ?></td>
                <td><?= $not->created_at ?></td>

                <td><a href='#' class="general_remove_item" data-tablename="App\models\notification_m" data-deleteurl="<?= url("/general_remove_item") ?>" data-itemnot_id="<?= $not->not_id ?>"><span class="label label-danger">Delete <i class="fa fa-remove"></i></span></a></td>
            </tr>
            <?php endforeach ?>
            </tbody>

        </table>
    </div>
</div>


@endsection
