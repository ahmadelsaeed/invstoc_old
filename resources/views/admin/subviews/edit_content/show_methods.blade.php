@extends('admin.main_layout')

@section('subview')

    <table id="cat_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <td>#</td>
            <td>Method Title</td>
            <td>Link</td>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($methods as $key => $method): ?>
        <tr>
            <td><?= $key++; ?></td>
            <td><?= $method->method_title ?></td>
            <td>
                <?php foreach ($all_langs as $lang_key => $lang_item): ?>
                    <a href="<?= url('/admin/edit_content/'.$lang_item->lang_id.'/'.$method->method_name) ?>">Edit_<?= $lang_item->lang_title ?></a>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?php endforeach; ?>
            </td>

        </tr>
        <?php endforeach ?>
        </tbody>

    </table>

@endsection
