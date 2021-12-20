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
                    <td>Image</td>
                    <td>Currency To</td>
                    <td>Currency Sign</td>
                    <?php if(false): ?>
                    <td>Current Rate</td>
                    <td>Last Calculated Date</td>
                    <td>Show in Homepage</td>
                    <td>Show in Menu</td>
                    <?php endif; ?>
                    <td>edit</td>
                    <td>delete</td>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($currencies as $key => $currency): ?>
                <tr id="row<?= $currency->id ?>">
                    <td><?= $key++; ?></td>
                    <td><img src="<?= get_image_or_default($currency->cur_img_path) ?>" width="24"></td>
                    <td><?= $currency->cur_to ?></td>
                    <td><?= $currency->cur_sign ?></td>
                    <?php if(false): ?>
                    <td><?= $currency->cur_rate ?></td>
                    <td><?= $currency->last_date ?></td>
                    <td>
                        <?php
                        echo generate_multi_accepters(
                                $accepturl="",
                                $item_obj=$currency,
                                $item_primary_col="id",
                                $accept_or_refuse_col="show_in_homepage",
                                $model='App\models\currency_rates_m'
                        );
                        ?>
                    </td>
                    <td>
                        <?php
                        echo generate_multi_accepters(
                                $accepturl="",
                                $item_obj=$currency,
                                $item_primary_col="id",
                                $accept_or_refuse_col="show_in_menu",
                                $model='App\models\currency_rates_m'
                        );
                        ?>
                    </td>
                    <?php endif; ?>
                    <td><a href="<?= url("admin/currencies/save_currency/$currency->id") ?>"><span class="label label-info">Edit <i class="fa fa-edit"></i></span></a></td>
                    <td><a href='#' class="general_remove_item" data-tablename="App\models\currency_rates_m" data-deleteurl="<?= url("/admin/currencies/delete_currency") ?>" data-itemid="<?= $currency->id ?>"><span class="label label-danger">Delete <i class="fa fa-remove"></i></span></a></td>
                </tr>
                <?php endforeach ?>
                </tbody>

            </table>
        </div>
    </div>



@endsection
