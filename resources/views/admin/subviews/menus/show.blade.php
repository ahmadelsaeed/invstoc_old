@extends('admin.main_layout')

@section("subview")

    <div class="panel panel-info">
        <div class="panel-heading">
            Menus
        </div>
        <div class="panel-body">
            <table id="cat_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <td>#</td>
                    <td>Menu Title</td>
                    <td>Edit</td>
                    <td>Remove</td>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <td>#</td>
                    <td>Menu Title</td>
                    <td>Edit</td>
                    <td>Remove</td>
                </tr>
                </tfoot>

                <tbody>
                <?php foreach ($all_menus as $key => $menu): ?>
                <tr id="row<?= $menu->menu_id ?>">
                    <td><?= $key+1 ?></td>
                    <td><?= $menu->menu_title ?></td>
                    <td><a href="<?= url("admin/menus/save_menu/$menu->lang_id/$menu->menu_id") ?>"><span class="label label-info">Edit <i class="fa fa-edit"></i></span></a></td>
                    <td><a href='#' class="general_remove_item" data-tablename="App\models\sortable_menus_m" data-deleteurl="<?= url("/admin/menus/delete_menu") ?>" data-itemid="<?= $menu->menu_id?>"><span class="label label-danger">Delete <i class="fa fa-remove"></i></span></a></td>
                </tr>
                <?php endforeach ?>
                </tbody>

            </table>

            <div class="row">
                <h2>Add Menu</h2>
                <?php foreach ($all_langs as $lang_key => $lang_item): ?>
                <a href="<?= url("/admin/menus/save_menu/$lang_item->lang_id") ?>">Add ({{$lang_item->lang_title}}) Menu</a>
                &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;
                <?php endforeach; ?>
            </div>
        </div>
    </div>



@endsection
