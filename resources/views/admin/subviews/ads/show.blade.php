@extends('admin.main_layout')

@section('subview')
    <div class="panel panel-info">
        <div class="panel-heading">
            advertisements
        </div>
        <div class="panel-body">
            <table id="cat_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <td>#</td>
                    <td>Ads Title</td>
                    <td>Ads Page Name</td>
                    <td>Ads Type</td>
                    <td>Edit</td>
                    <td>Delete</td>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($ads as $key => $ad): ?>
                <tr id="row<?= $ad->id ?>">
                    <td><?= $key++; ?></td>
                    <td><?= $ad->ads_title ?></td>
                    <td><?= $ad->page_name ?></td>
                    <td><?= $ad->ad_show ?></td>

                    <td><a href="<?= url("admin/ads/save_ad/$ad->id") ?>"><span class="label label-info">Edit <i class="fa fa-edit"></i></span></a></td>
                    <td><a href='#' class="general_remove_item" data-tablename="App\models\ads_m" data-deleteurl="<?= url("/admin/ads/remove_ads") ?>" data-itemid="<?= $ad->id ?>"><span class="label label-danger">Delete <i class="fa fa-remove"></i></span></a></td>
                </tr>
                <?php endforeach ?>
                </tbody>

            </table>
        </div>
    </div>
@endsection
