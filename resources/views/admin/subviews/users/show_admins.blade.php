@extends('admin.main_layout')

@section('subview')

    <div class="panel panel-info">
        <div class="panel-heading">
            Admins
        </div>
        <div class="panel-body">
            <table id="cat_table_1" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <td>#</td>
                    <td>Email</td>
                    <td>User Name</td>
                    <td>Full Name</td>
                    <td>User Type</td>
                    <td>Created At</td>
                    <td>Permissions</td>
                    <td>Edit</td>
                    <td>Remove</td>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <td>#</td>
                    <td>Email</td>
                    <td>User Name</td>
                    <td>Full Name</td>
                    <td>User Type</td>
                    <td>Created At</td>
                    <td>Permissions</td>
                    <td>Edit</td>
                    <td>Remove</td>
                </tr>
                </tfoot>

                <tbody>
                <?php foreach ($users as $key => $user): ?>
                <tr id="row<?= $user->user_id ?>">
                    <td><?=$key+1?></td>
                    <td><?=$user->email ?></td>
                    <td><?=$user->username ?></td>
                    <td><?=$user->full_name ?></td>
                    <td><?=$user->user_type ?></td>
                    <td>{{dump_date($user->created_at)}}</td>
                    <td>
                        <a href="<?= url("admin/users/assign_permission/$user->user_id") ?>">
                            <span class="label label-info"> Edit Permissions <i class="fa fa-edit"></i></span>
                        </a>
                    </td>
                    <td>
                        <a href="<?= url("admin/users/save/$user->user_id") ?>">
                            <span class="label label-info"> Edit <i class="fa fa-edit"></i></span>
                        </a>
                    </td>
                    <td>
                        <a href="#" class="general_remove_item" data-deleteurl="<?= url("/admin/users/remove_admin") ?>" data-tablename="App\User"  data-itemid="<?= $user->user_id ?>">
                            <span class="label label-danger"> Remove <i class="fa fa-remove"></i></span>
                        </a>
                    </td>


                </tr>
                <?php endforeach ?>
                </tbody>

            </table>
        </div>
    </div>



@endsection
