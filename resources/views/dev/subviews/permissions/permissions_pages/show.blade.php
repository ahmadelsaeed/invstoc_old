@extends('dev.main_layout')

@section('subview')

    <div class="panel panel-info">
        <div class="panel-heading">
            All Permission Pages
        </div>
        <div class="panel-body">
            <table id="cat_table_1" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <td>#</td>
                    <td>Permission Page</td>
                    <td>Sub System</td>
                    <td>Show in AdminPanel</td>
                    <td>Edit</td>
                    <td>Remove</td>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <td>#</td>
                    <td>Permission Page</td>
                    <td>Sub System</td>
                    <td>Show in AdminPanel</td>
                    <td>Edit</td>
                    <td>Remove</td>
                </tr>
                </tfoot>

                <tbody>
                <?php foreach ($all_permissions_pages as $key => $page): ?>
                <tr id="row<?= $page->per_page_id ?>">
                    <td><?=$key+1?></td>
                    <td><?=$page->page_name ?></td>
                    <td><?=$page->sub_sys ?></td>
                    <td>
                        <?php
                        echo
                        generate_multi_accepters(
                            $accepturl="",
                            $item_obj=$page,
                            $item_primary_col="per_page_id",
                            $accept_or_refuse_col="show_in_admin_panel",
                            $model='App\models\permissions\permission_pages_m',
                            $accepters_data=["0"=>"Refused","1"=>"Accepted"]
                        );
                        ?>
                    </td>
                    <td>
                        <a href="<?= url("dev/permissions/permissions_pages/save/$page->per_page_id") ?>">
                            <span class="btn btn-info"> Edit Permissions </span>
                        </a>
                    </td>
                    <td>
                        <a href='#' class="btn btn-danger general_remove_item" data-deleteurl="<?= url("/dev/permissions/permissions_pages/delete") ?>" data-tablename="App\models\permissions\permission_pages_m"  data-itemid="<?= $page->per_page_id ?>">
                            Delete
                        </a>
                    </td>


                </tr>
                <?php endforeach ?>
                </tbody>

            </table>
        </div>
    </div>



@endsection
