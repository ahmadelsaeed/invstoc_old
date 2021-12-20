@extends('admin.main_layout')

@section('subview')

    <div class="panel panel-info">
        <div class="panel-heading">
            All Currencies Based on USD
        </div>
        <div class="panel-body">

            <table id="cat_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <td>#</td>
                    <td>Pair Currency Name</td>
                    <td>Edit</td>
                    <td>Delete</td>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($pair_currencies as $key => $pair_currency): ?>
                <tr id="row<?= $pair_currency->pair_currency_id ?>">
                    <td><?= $key+1; ?></td>
                    <td><?= $pair_currency->pair_currency_name ?></td>
                    <td><a href="<?= url("admin/pair_currency/save_pair_currency/$pair_currency->pair_currency_id") ?>"><span class="label label-info">Edit <i class="fa fa-edit"></i></span></a></td>
                    <td><a href='#' class="general_remove_item" data-tablename="App\models\pair_currency_m" data-deleteurl="<?= url("/admin/pair_currency/delete_pair_currency") ?>" data-itemid="<?= $pair_currency->pair_currency_id ?>"><span class="label label-danger">Delete <i class="fa fa-remove"></i></span></a></td>
                </tr>
                <?php endforeach ?>
                </tbody>

            </table>
        </div>
    </div>



@endsection
