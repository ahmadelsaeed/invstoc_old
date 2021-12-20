@extends('admin.main_layout')

@section('subview')

    <div class="panel panel-info">
        <div class="panel-heading">
            Languages
        </div>
        <div class="panel-body">
            <div class="alert alert-info ">
                Note: First Language at this table it will be the main language of site
            </div>

            <table id="cat_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <td>#</td>
                    <td>Language Image</td>
                    <td>Language Symbol</td>
                    <td>Language Display Text</td>
                    <td>Edit</td>
                    <td>Delete</td>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($show_all_langs as $key => $lang): ?>
                <tr id="row<?= $lang->lang_id ?>">
                    <td><?= $key++; ?></td>
                    <?php
                    $img_url = url('public_html/img/no_img.png');
                    if (file_exists($lang->lang_img_path))
                    {
                        $img_url = url($lang->lang_img_path);
                    }
                    ?>
                    <td><img src="<?= url($img_url) ?>" width="50"></td>
                    <td><?= $lang->lang_title ?></td>
                    <td><?= $lang->lang_text ?></td>
                    <td><a href="<?= url("admin/langs/save_lang/$lang->lang_id") ?>"><span class="label label-info">Edit <i class="fa fa-edit"></i></span></a></td>
                    <td><a href='#' class="general_remove_item" data-tablename="App\models\langs_m" data-deleteurl="<?= url("/admin/langs/delete_lang") ?>" data-itemid="<?= $lang->lang_id ?>"><span class="label label-danger">Delete <i class="fa fa-remove"></i></span></a></td>
                </tr>
                <?php endforeach ?>
                </tbody>

            </table>
        </div>
    </div>



@endsection
