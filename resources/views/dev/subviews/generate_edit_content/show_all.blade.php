@extends('dev.main_layout')

@section('subview')

    <div class="panel panel-info">
        <div class="panel-heading">
            All Generators
        </div>

        <div class="panel-body">
            <table id="cat_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <td>method_name</td>
                    <td>Edit Content Link</td>
                    <td>Edit</td>
                    <td>Remove</td>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($all_methods as $key => $method): ?>
                <tr id="row<?= $method->id ?>">
                    <td><?= $method->method_name ?></td>
                    <td>
                        <?php foreach ($all_langs as $lang_key => $lang_item): ?>
                        <a href="{{url("/admin/edit_content/$lang_item->lang_id/$method->method_name")}}">Link_<?=$lang_item->lang_title;?></a>
                        <?php endforeach; ?>
                    </td>

                    <td><a class="btn btn-info" href="<?= url("/dev/generate_edit_content/save/$method->id") ?>">Edit</a></td>
                    <td><a href='#' class="btn btn-danger general_remove_item" data-tablename="App\models\generate_site_content_methods_m" data-deleteurl="<?= url("/general_remove_item") ?>" data-itemid="<?= $method->id ?>">Remove</a></td>

                </tr>
                <?php endforeach ?>
                </tbody>

            </table>
        </div>
    </div>





@endsection